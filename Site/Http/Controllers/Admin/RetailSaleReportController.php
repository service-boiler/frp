<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Exports\Excel\RetailSaleReportExcel;
use ServiceBoiler\Prf\Site\Filters\RetailSaleReport\FerroliManagersRetailSaleReportFilter;
use ServiceBoiler\Prf\Site\Filters\RetailSaleReport\RetailSaleReportUserFilter;
use ServiceBoiler\Prf\Site\Filters\FileType\ModelHasFilesFilter;
use ServiceBoiler\Prf\Site\Filters\RetailSaleReport\RetailSaleReportPerPageFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\RetailSaleReportRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Models\RetailSaleReport;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\RetailSaleReportRepository;
use ServiceBoiler\Prf\Site\Exceptions\Digift\DigiftException;
use ServiceBoiler\Prf\Site\Support\CommentBox;

class RetailSaleReportController extends Controller
{

	use StoreMessages, ValidatesRequests, AuthorizesRequests;

	/**
	 * @var RetailSaleReportRepository
	 */
	private $retail_sale_reports;
	/**
	 * @var FileTypeRepository
	 */
	private $types;

	/**
	 * RetailSaleReportController constructor.
	 *
	 * @param RetailSaleReportRepository $retail_sale_reports
	 * @param FileTypeRepository $types
	 */
	public function __construct(
		RetailSaleReportRepository $retail_sale_reports,
		FileTypeRepository $types
	)
	{

		$this->retail_sale_reports = $retail_sale_reports;
		$this->types = $types;
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	public function index(Request $request)
	{
		$this->retail_sale_reports->trackFilter();
		$this->retail_sale_reports->applyFilter(new FerroliManagersRetailSaleReportFilter());

		$this->retail_sale_reports->pushTrackFilter(RetailSaleReportUserFilter::class);
		$this->retail_sale_reports->pushTrackFilter(RetailSaleReportPerPageFilter::class);
		$retail_sale_reports = $this->retail_sale_reports->paginate($request->input('filter.per_page', config('site.per_page.retail_sale_report', 10)), ['retail_sale_reports.*']);
		$repository = $this->retail_sale_reports;

		if ($request->has('excel')) {
			(new RetailSaleReportExcel())->setRepository($this->retail_sale_reports)->render();
		}

		return view('site::admin.retail_sale_report.index', compact('retail_sale_reports', 'repository'));
	}

	/**
	 * @param RetailSaleReport $retail_sale_report
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(RetailSaleReport $retail_sale_report)
	{
        $this->authorize('view', $retail_sale_report);
		$this->types->applyFilter((new ModelHasFilesFilter())->setId($retail_sale_report->id)->setMorph('retailSaleReports'));
		$file_types = $this->types->all();
		$files = $retail_sale_report->files;
		$retail_sale_report_statuses = $retail_sale_report->statuses();
		$digift_user = [];
		if ($retail_sale_report->user->digiftUser()->doesntExist()) {
			$digift_user = $retail_sale_report->user->getDigiftUserData();
		}
		$commentBox = new CommentBox($retail_sale_report);

		return view('site::admin.retail_sale_report.show', compact(
			'digift_user',
			'retail_sale_report',
			'file_types',
			'files',
			'retail_sale_report_statuses',
			'commentBox'
		));
	}

	/**
	 * @param RetailSaleReportRequest $request
	 * @param RetailSaleReport $retail_sale_report
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(RetailSaleReportRequest $request, RetailSaleReport $retail_sale_report)
	{  

		$type = 'success';
		$message = trans('site::retail_sale_report.updated');

		try {
			$request->update($retail_sale_report);
		} catch (GuzzleException $e) {
			$type = 'error';
			$message = trans('site::digift.error.admin.guzzle', ['message' => $e->getMessage()]);
		} catch (DigiftException $e) {
			$type = 'error';
			$message = trans('site::digift.error.admin.digift', ['message' => $e->getMessage()]);
		} catch (\Exception $e) {
			$type = 'error';
			$message = trans('site::digift.error.admin.unknown', ['message' => $e->getMessage()]);
		} finally {
			return redirect()->route('admin.retail-sale-reports.show', $retail_sale_report)->with($type, $message);
		}

	}

	/**
	 * @param \ServiceBoiler\Prf\Site\Http\Requests\MessageRequest $request
	 * @param \ServiceBoiler\Prf\Site\Models\RetailSaleReport $retail_sale_report
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function message(MessageRequest $request, RetailSaleReport $retail_sale_report)
	{
		return $this->storeMessage($request, $retail_sale_report);
	}

}