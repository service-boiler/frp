<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Filters\RetailOrder\FerroliManagerRetailOrderFilter;
use ServiceBoiler\Prf\Site\Filters\RetailOrder\RetailOrderPerPageFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\RetailOrderRequest;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\RetailOrder;
use ServiceBoiler\Prf\Site\Repositories\RetailOrderRepository;

class RetailOrderController extends Controller
{

    use StoreMessages;

    /**
     * @var RetailOrderRepository
     */
    private $retailOrders;

    /**
     * RetailOrderController constructor.
     * @param RetailOrderRepository $retailOrders
     */
    public function __construct(RetailOrderRepository $retailOrders)
    {

        $this->retailOrders = $retailOrders;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->retailOrders->trackFilter();
        $this->retailOrders->pushTrackFilter(RetailOrderPerPageFilter::class);
        $retailOrders = $this->retailOrders->paginate($request->input('filter.per_page', config('site.per_page.retail_order', 100)), ['retail_orders.*']);
        $repository = $this->retailOrders;

        return view('site::admin.retail_order.index', compact('retailOrders', 'repository'));
    }

    /**
     * @param RetailOrder $retailOrder
     * @return \Illuminate\Http\Response
     */
    public function show(RetailOrder $retailOrder)
    {

        return view('site::admin.retail_order.show', compact(
            'retailOrder'
        ));
    }


    /**
     * @param RetailOrder $retailOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(RetailOrder $retailOrder)
    {
        $countries = Country::query()->where('enabled', 1)->orderBy('name')->get();
        $retailOrderStatuses = RetailOrderStatus::query()->orderBy('sort_order')->get();

        return view('site::admin.retail_order.edit', compact('retailOrder', 'countries', 'retailOrderStatuses'));
    }

    /**
     * @param  RetailOrderRequest $request
     * @param  RetailOrder $retailOrder
     * @return \Illuminate\Http\Response
     */
    public function update(RetailOrderRequest $request, RetailOrder $retailOrder)
    {
        $retailOrder->update($request->input('retailOrder'));

        return redirect()->route('admin.retailOrders.show', $retailOrder)->with('success', trans('site::retail_order.updated'));
    }


}