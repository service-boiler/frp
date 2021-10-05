<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Prf\Site\Exports\Excel\DistributorSaleExcel;

use ServiceBoiler\Prf\Site\Filters\FerroliManagerAttachFilter;
use ServiceBoiler\Prf\Site\Filters\DistributorSale\DistributorSaleUserFilter;
use ServiceBoiler\Prf\Site\Filters\DistributorSale\DistributorSaleBelongsUserFilter;
use ServiceBoiler\Prf\Site\Filters\DistributorSale\FerroliManagersDistributorSaleFilter;
use ServiceBoiler\Prf\Site\Filters\DistributorSale\DistributorSaleUserRoleFilter;
use ServiceBoiler\Prf\Site\Filters\User\UserHasDistributorSaleFilter;

use ServiceBoiler\Prf\Site\Http\Requests\DistributorSaleRequest;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\DistributorSaleWeek;
use ServiceBoiler\Prf\Site\Models\DistributorSale;
use ServiceBoiler\Prf\Site\Repositories\DistributorSaleRepository;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;
use Illuminate\Http\Request;

class DistributorSaleController extends Controller
{

    use AuthorizesRequests;

    /**
     * @var DistributorSaleRepository
     */
    protected $distributorSales;
    /**
     * @var DistributorSaleProductRepository
     */
    protected $products;

    /**
     * Create a new controller instance.
     *
     * @param DistributorSaleRepository $distributorSales
     * @param DistributorSaleProductRepository $products
     */
    public function __construct(DistributorSaleRepository $distributorSales, UserRepository $users)
    {
        $this->distributorSales = $distributorSales;
        $this->users = $users;
        
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
                 
        $this->distributorSales->trackFilter();
		$this->distributorSales->applyFilter(new FerroliManagersDistributorSaleFilter());
        $this->distributorSales->pushTrackFilter(DistributorSaleUserFilter::class);
        $this->distributorSales->pushTrackFilter(DistributorSaleUserRoleFilter::class);
        
        if ($request->has('excel')) {
			(new DistributorSaleExcel())->setRepository($this->distributorSales->orderBy(['week_id'],'desc'))->render();
		}
        
        $allweeks = DistributorSaleWeek::query()->where('date_from','<',Carbon::now())->orderByDesc('date_from')->get();
        //$users = User::has('distributorSales')->get();
		$this->users->applyFilter(new FerroliManagerAttachFilter);
		$this->users->applyFilter(new UserHasDistributorSaleFilter);
        $users = $this->users->all();
        
        return view('site::admin.distributor_sales.index', [
            'repository'  => $this->distributorSales,
            'distributorSales' => $this->distributorSales->paginate($request->input('filter.per_page', config('site.per_page.repair', 10000)), ['distributor_sales.*']),
            'allweeks' => $allweeks, 
            'users' => $users
        ]);
    }

	/**
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	public function excel()
	{

		$this->distributor_sales->applyFilter(new DistributorSaleUserExcelFilter());
		(new DistributorSaleExcelUser())->setRepository($this->distributor_sales)->render();

	}

	/**
	 * @param Request $request
	 * @param DistributorSale $storehouse
	 *
	 * @return \Illuminate\View\View
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function show(Request $request, DistributorSale $storehouse)
    {
        $this->authorize('view', $storehouse);

        $this->products->trackFilter();
        $repository = $this->products->applyFilter((new DistributorSaleFilter())->setDistributorSaleId($storehouse->getKey()));

        $products = $repository->paginate(
            $request->input('filter.per_page', config('site.per_page.storehouse_product', 10)),
            ['storehouse_products.*']
        );
        return view('site::storehouse.show', compact('storehouse', 'products', 'repository'));
    }

	/**
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function create()
    {

        $this->authorize('create', DistributorSale::class);
        $addresses = auth()->user()->addresses()->where('type_id', 6)->get();

        return view('site::storehouse.create', compact('addresses'));
    }

    /**
     * @param  DistributorSaleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(DistributorSaleRequest $request)
    {
        $storehouse = $request->user()->distributor_sales()->create(array_merge(
            $request->input('storehouse'),
            [
                'enabled'  => $request->filled('storehouse.enabled'),
                'everyday' => $request->filled('storehouse.everyday'),
            ]
        ));

        $storehouse->attachAddresses($request);

        return redirect()->route('distributor_sales.show', $storehouse)->with('success', trans('site::storehouse.created'));
    }

	/**
	 * @param DistributorSale $storehouse
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function edit(DistributorSale $storehouse)
    {
        $this->authorize('edit', $storehouse);
        $addresses = auth()->user()->addresses()->where('type_id', 6)->get();
        return view('site::storehouse.edit', compact('storehouse', 'addresses'));
    }

    /**
     * @param  DistributorSaleRequest $request
     * @param DistributorSale $storehouse
     * @return \Illuminate\Http\Response
     */
    public function update(DistributorSaleRequest $request, DistributorSale $storehouse)
    {
        $storehouse->update(array_merge(
            $request->input('storehouse'),
            [
                'enabled'  => $request->filled('storehouse.enabled'),
                'everyday' => $request->filled('storehouse.everyday'),
            ]
        ));

        $storehouse->attachAddresses($request);

        return redirect()->route('distributor_sales.show', $storehouse)->with('success', trans('site::storehouse.updated'));
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  DistributorSale $storehouse
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function destroy(DistributorSale $storehouse)
    {

        $this->authorize('delete', $storehouse);

        if ($storehouse->delete()) {
            Session::flash('success', trans('site::storehouse.deleted'));
            $redirect = route('distributor_sales.index');
        } else {
            Session::flash('error', trans('site::storehouse.error.deleted'));
            $redirect = route('distributor_sales.show', $storehouse);
        }
        $json['redirect'] = $redirect;

        return response()->json($json);

    }

}