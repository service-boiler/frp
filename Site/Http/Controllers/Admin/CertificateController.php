<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use ServiceBoiler\Prf\Site\Filters\Certificate\CertificatePerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Certificate\CertificateSortByDateFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\CertificateRequest;
use ServiceBoiler\Prf\Site\Models\Certificate;
use ServiceBoiler\Prf\Site\Models\CertificateType;
use ServiceBoiler\Prf\Site\Repositories\CertificateRepository;

class CertificateController extends Controller
{

    protected $certificates;

    /**
     * Create a new controller instance.
     *
     * @param CertificateRepository $certificates
     */
    public function __construct(CertificateRepository $certificates)
    {
        $this->certificates = $certificates;
    }

    /**
     * Show the user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->certificates->trackFilter();
        $this->certificates->pushTrackFilter(CertificatePerPageFilter::class);
        $this->certificates->pushTrackFilter(CertificateSortByDateFilter::class);
        $certificate_types = CertificateType::query()->get();

        return view('site::admin.certificate.index', [
            'certificate_types' => $certificate_types,
            'repository'        => $this->certificates,
            'certificates'      => $this->certificates->paginate($request->input('filter.per_page', config('site.per_page.certificate', 500)), ['certificates.*'])
        ]);
    }

    /**
     * @param CertificateType $certificate_type
     * @return \Illuminate\Http\Response
     */
    public function create(CertificateType $certificate_type)
    {
        return view('site::admin.certificate.create', compact('certificate_type'));
    }

    /**
     * @param CertificateRequest $request
     * @param CertificateType $certificate_type
     * @return \Illuminate\Http\Response
     */
    public function store(CertificateRequest $request, CertificateType $certificate_type)
    {

        $inputFileType = ucfirst($request->path->getClientOriginalExtension());
        //$filterSubset = new CertificateLoadFilter();
        /** @var BaseReader $reader */
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        //$reader->setReadFilter($filterSubset);

        $spreadsheet = $reader->load($request->path->getPathname());

        $rowIterator = $spreadsheet->getActiveSheet()->getRowIterator();

        foreach ($rowIterator as $r => $row) {


            $cellIterator = $row->getCellIterator();

            $id = $name = $organization = false;

            foreach ($cellIterator as $c => $cell) {


                switch ($c) {
                    case 'A':
                        $id = (string)trim($cell->getValue());
                        break;

                    case 'B':
                        $name = (string)trim($cell->getValue());
                        break;

                    case 'C':
                        $organization = (string)$cell->getValue();
                        break;

                }
            }
            if ($id !== false && $name !== false && $organization !== false) {
                DB::table('certificates')
                    ->updateOrInsert(
                        ['id' => $id],
                        [
                            'name'         => $name,
                            'organization' => $organization,
                            'type_id'      => $certificate_type->getAttribute('id'),
                            'created_at'   => Carbon::now(),
                        ]
                    );
            }
        }

        return redirect()->route('admin.certificates.create', $certificate_type)->with('success', trans('site::certificate.loaded'));
    }

    /**
     * @param  Certificate $certificate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Certificate $certificate)
    {

        if ($certificate->delete()) {
            Session::flash('success', trans('site::certificate.deleted'));

        } else {
            Session::flash('error', trans('site::certificate.error.deleted'));
        }
        $json['redirect'] = route('admin.certificates.index');;

        return response()->json($json);

    }

}