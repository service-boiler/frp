<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Events\RetailSaleReportCreateEvent;
use ServiceBoiler\Prf\Site\Filters\BelongsUserFilter;
use ServiceBoiler\Prf\Site\Filters\FileType\ModelHasFilesFilter;
use ServiceBoiler\Prf\Site\Filters\RetailSaleReport\RetailSaleReportPerPageFilter;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\RetailSaleReportRequest;
use ServiceBoiler\Prf\Site\Http\Resources\ProductResource;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Models\RetailSaleReport;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Repositories\AddressRepository;
use ServiceBoiler\Prf\Site\Repositories\ContragentRepository;
use ServiceBoiler\Prf\Site\Repositories\CountryRepository;
use ServiceBoiler\Prf\Site\Repositories\EquipmentRepository;
use ServiceBoiler\Prf\Site\Repositories\FileRepository;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\RetailSaleReportRepository;


class RetailSaleReportController extends Controller
{

    use AuthorizesRequests, StoreMessages;
    /**
     * @var RetailSaleReportRepository
     */
    protected $reports;
   
   protected $addresses;
    
 
    /**
     * @var FileTypeRepository
     */
    protected $types;
    /**
     * @var FileRepository
     */
    protected $files;
    /**
     * @var CountryRepository
     */
    private $countries;
    /**
     * @var EquipmentRepository
     */
    private $equipments;
    /**
     * @var ContragentRepository
     */
    private $contragents;


    /**
     * Create a new controller instance.
     *
     * @param RetailSaleReportRepository $reports
     * @param EngineerRepository $engineers
     * @param TradeRepository $trades
     * @param FileTypeRepository $types
     * @param CountryRepository $countries
     * @param EquipmentRepository $equipments
     * @param FileRepository $files
     * @param ContragentRepository $contragents
     */
    public function __construct(
        RetailSaleReportRepository $retail_sale_reports,
        AddressRepository $addresses,
        FileTypeRepository $types,
        CountryRepository $countries,
        EquipmentRepository $equipments,
        FileRepository $files,
        ContragentRepository $contragents

    )
    {
        $this->retail_sale_reports = $retail_sale_reports;
        $this->addresses = $addresses;
        $this->types = $types;
        $this->files = $files;
        $this->countries = $countries;
        $this->equipments = $equipments;
        $this->contragents = $contragents;
    }

    /**
     * @param RetailSaleReportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(RetailSaleReportRequest $request)
    {

        $this->retail_sale_reports->trackFilter();
        $this->retail_sale_reports->applyFilter(new BelongsUserFilter());
        $this->retail_sale_reports->pushTrackFilter(RetailSaleReportPerPageFilter::class);

        return view('site::retail_sale_report.index', [
            'repository' => $this->retail_sale_reports,
            'retail_sale_reports'  => $this->retail_sale_reports->paginate($request->input('filter.per_page', config('site.per_page.reports', 100)), ['retail_sale_reports.*'])
        ]);
    }


	/**
	 * @param RetailSaleReport $retail_sale_report
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function show(RetailSaleReport $retail_sale_report)
    {   
        $this->authorize('view', $retail_sale_report);
        $this->types->applyFilter((new ModelHasFilesFilter())->setId($retail_sale_report->id)->setMorph('retailSaleReports'));
        $file_types = $this->types->all();
        $files = $retail_sale_report->files;

        return view('site::retail_sale_report.show', compact(
            'retail_sale_report',
            'file_types',
            'files'
        ));
    }

	/**
	 * @param RetailSaleReportRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function create(RetailSaleReportRequest $request)
    {

        $this->authorize('create', RetailSaleReport::class);
        $user= $request->user();
        $contragents = $request->user()->contragents()->orderBy('name')->get();
        if($request->user()->parent) {
            $addresses = $request->user()->parent->addresses()->orderBy('full')->get();
        } else {
        $addresses =  [];
        }
        
        $countries = Country::query()->where('enabled', 1)->orderBy('name')->get();
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 9)->orderBy('sort_order')->get();
        $products = Product::query()
            ->retailSaleBonusabled()
            ->orderBy('name')
            ->with('mounting_bonus')
            ->get();
        
        $files = $this->getFiles($request);

        $selected_product = Product::query()->findOrNew(old('mounting.product_id'));
               
        $user_motivation_status = $user->userMotivationSaleStatus;
        //dd($user_motivation_status);
        //dd($user->reports->where('created_at','>',Carbon::now()->subDays(90))->count());
        //dd($user->userMotivationStatus);
        return view('site::retail_sale_report.create', compact(
            'contragents',
            'countries',
            'addresses',
            'file_types',
            'files',
            'selected_product',
            'products',
            'user_motivation_status'
        ));
    }

    /**
     * @param RetailSaleReportRequest $request
     * @param RetailSaleReport|null $retail_sale_report
     * @return \Illuminate\Support\Collection
     */
    private function getFiles(RetailSaleReportRequest $request, RetailSaleReport $retail_sale_report = null)
    {
        $files = collect([]);
        $old = $request->old('file');
        
        if (!is_null($old) && is_array($old)) {
            foreach ($old as $type_id => $values) {
                foreach ($values as $file_id) {
                    $files->push(File::query()->findOrFail($file_id));
                }
            }
        } elseif (!is_null($retail_sale_report)) {
            $files = $files->merge($retail_sale_report->files);
        }

        return $files;
    }

    /**
     * @param  RetailSaleReportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(RetailSaleReportRequest $request)
    {   
           //dd($request);
        $report = $request->user()->retailSaleReports()->create($request->input('report'));

        $this->setFiles($request, $report);

        //event(new RetailSaleReportCreateEvent($report));

        return redirect()->route('retail-sale-reports.index')->with('success', trans('site::retail_sale_report.created'));
    }

    /**
     * @param RetailSaleReportRequest $request
     * @param RetailSaleReport $mounting
     */
    private function setFiles(RetailSaleReportRequest $request, RetailSaleReport $retail_sale_report)
    {
        $retail_sale_report->detachFiles();
        if ($request->filled('file')) {
            foreach ($request->input('file') as $type_id => $values) {
                foreach ($values as $file_id) {
                    $retail_sale_report->files()->save(File::find($file_id));
                }
            }
        }
    }

    /**
     * @param \ServiceBoiler\Prf\Site\Http\Requests\MessageRequest $request
     * @param \ServiceBoiler\Prf\Site\Models\RetailSaleReport $mounting
     * @return \Illuminate\Http\JsonResponse
     */
    public function message(MessageRequest $request, RetailSaleReport $retail_sale_report)
    {
        return $this->storeMessage($request, $retail_sale_report);
    }

}