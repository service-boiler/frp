<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Filters\RetailOrder\RetailOrderUserEsbFilter;
use ServiceBoiler\Prf\Site\Filters\RetailOrder\RetailOrderPerPageFilter;
use ServiceBoiler\Prf\Site\Http\Requests\RetailOrderRequest;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\RetailOrder;
use ServiceBoiler\Prf\Site\Repositories\RetailOrderRepository;

class RetailOrderEsbController extends Controller
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
        $this->retailOrders->applyFilter(new RetailOrderUserEsbFilter());
        $this->retailOrders->pushTrackFilter(RetailOrderPerPageFilter::class);
        $retailOrders = $this->retailOrders->paginate($request->input('filter.per_page', 100), ['retail_orders.*']);
        $repository = $this->retailOrders;

        return view('site::retail_order.index_esb', compact('retailOrders', 'repository'));
    }

   

	public function status(RetailOrderRequest $request, RetailOrder $retailOrder)
	{
   
            if(in_array($request->input('retailOrder.status_id'),$retailOrder->statuses()->pluck('id')->toArray()))
            {
                $retailOrder->status_id=$request->input('retailOrder.status_id');
                $retailOrder->save();
                //event(new RetailOrderStatusChangeEvent($order));
            }
            
		
		return redirect()->route('retail-orders-esb.index')->with('success', trans('site::order.updated'));

	}


}