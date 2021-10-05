<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Exports\Excel\StorehouseExcelIndex;
use ServiceBoiler\Prf\Site\Exports\Excel\StorehouseExcelShow;
use ServiceBoiler\Prf\Site\Filters\Storehouse\FerroliManagersStorehouseFilter;
use ServiceBoiler\Prf\Site\Filters\Storehouse\StorehouseBoolEverydayFilter;
use ServiceBoiler\Prf\Site\Filters\Storehouse\StorehouseDateUploadedFromFilter;
use ServiceBoiler\Prf\Site\Filters\Storehouse\StorehouseDateUploadedToFilter;
use ServiceBoiler\Prf\Site\Filters\Storehouse\StorehousePerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Storehouse\StorehouseProductGroupTypeFilter;
use ServiceBoiler\Prf\Site\Filters\Storehouse\StorehouseProductSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Storehouse\StorehouseRegionSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Storehouse\StorehouseUserFilter;
use ServiceBoiler\Prf\Site\Filters\Storehouse\StorehouseUserRoleFilter;
use ServiceBoiler\Prf\Site\Filters\Storehouse\StorehouseUserSearchFilter;
use ServiceBoiler\Prf\Site\Filters\StorehouseProduct\StorehouseFilter;
use ServiceBoiler\Prf\Site\Http\Requests\StorehouseRequest;
use ServiceBoiler\Prf\Site\Repositories\StorehouseProductRepository;
use ServiceBoiler\Prf\Site\Repositories\StorehouseRepository;
use ServiceBoiler\Prf\Site\Models\Storehouse;

class StorehouseController extends Controller
{

	use AuthorizesRequests;
	/**
	 * @var StorehouseRepository
	 */
	protected $storehouses;

	/**
	 * @var StorehouseProductRepository
	 */
	private $products;

	/**
	 * Create a new controller instance.
	 *
	 * @param StorehouseRepository $storehouses
	 * @param StorehouseProductRepository $products
	 */
	public function __construct(StorehouseRepository $storehouses, StorehouseProductRepository $products)
	{
		$this->storehouses = $storehouses;
		$this->products = $products;
	}

	/**
	 * Show the user profile
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	public function index(Request $request)
	{

		$this->storehouses->trackFilter();
		$this->storehouses->applyFilter(new FerroliManagersStorehouseFilter());
		$this->storehouses->pushTrackFilter(StorehouseRegionSelectFilter::class);
		$this->storehouses->pushTrackFilter(StorehouseProductSearchFilter::class);
		$this->storehouses->pushTrackFilter(StorehouseProductGroupTypeFilter::class);
		$this->storehouses->pushTrackFilter(StorehouseDateUploadedFromFilter::class);
		$this->storehouses->pushTrackFilter(StorehouseDateUploadedToFilter::class);
		$this->storehouses->pushTrackFilter(StorehouseBoolEverydayFilter::class);
		$this->storehouses->pushTrackFilter(StorehouseUserFilter::class);
		$this->storehouses->pushTrackFilter(StorehouseUserRoleFilter::class);
		$this->storehouses->pushTrackFilter(StorehouseUserSearchFilter::class);
		$this->storehouses->pushTrackFilter(StorehousePerPageFilter::class);
		if ($request->has('excel')) {
			(new StorehouseExcelIndex())->setRepository($this->storehouses)->render();
		}

		return view('site::admin.storehouse.index', [
			'repository' => $this->storehouses,
			'storehouses' => $this->storehouses->paginate(
				$request->input('filter.per_page', config('site.per_page.storehouse', 100)),
				['storehouses.*']
			),
		]);
	}

	/**
	 * @param Request $request
	 * @param Storehouse $storehouse
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	public function show(Request $request, Storehouse $storehouse)
	{
		
        $this->authorize('view', $storehouse);
        $repository = $this->products->applyFilter((new StorehouseFilter())->setStorehouseId($storehouse->getKey()));
		if ($request->has('excel')) {
			(new StorehouseExcelShow())->setModel($storehouse)->render();
		}
		$products = $repository->paginate(
			$request->input('filter.per_page', config('site.per_page.storehouse_product', 10)),
			['storehouse_products.*']
		);

		return view('site::admin.storehouse.show', compact('storehouse', 'products', 'repository'));
	}

	/**
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function create()
	{
		$this->authorize('create', Storehouse::class);
		$addresses = auth()->user()->addresses()->where('type_id', 6)->get();

		return view('site::admin.storehouse.create', compact('addresses'));

	}

	/**
	 * @param  StorehouseRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(StorehouseRequest $request)
	{
		/** @var Storehouse $storehouse */
		$storehouse = $request->user()->storehouses()->create($request->input('storehouse'));

		$storehouse->attachAddresses($request);

		return redirect()->route('admin.storehouses.show', $storehouse)->with('success', trans('site::storehouse.created'));
	}
}