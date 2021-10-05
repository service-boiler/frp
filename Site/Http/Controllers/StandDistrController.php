<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use ServiceBoiler\Prf\Site\Concerns\StoreFiles;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Events\StandOrderStatusChangeEvent;
use ServiceBoiler\Prf\Site\Events\StandOrderCreateEvent;
use ServiceBoiler\Prf\Site\Events\StandOrderPaymentEvent;
use ServiceBoiler\Prf\Site\Events\StandOrderUserConfirmEvent;
use ServiceBoiler\Prf\Site\Filters\BelongsUserFilter;
use ServiceBoiler\Prf\Site\Filters\StandOrder\StandOrderAddressSelectFilter;
use ServiceBoiler\Prf\Site\Filters\StandOrder\StandOrderDistributorFilter;
use ServiceBoiler\Prf\Site\Filters\StandOrder\ScSearchFilter;
use ServiceBoiler\Prf\Site\Filters\OrderDateFilter;
use ServiceBoiler\Prf\Site\Http\Requests\FileRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\StandOrderLoadRequest;
use ServiceBoiler\Prf\Site\Http\Requests\StandOrderRequest;
use ServiceBoiler\Prf\Site\Models\StandOrder;
use ServiceBoiler\Prf\Site\Models\StandOrderItem;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Repositories\StandDistrRepository;
use ServiceBoiler\Prf\Site\Support\StandOrderLoadFilter;

class StandDistrController extends Controller
{

	use  AuthorizesRequests, StoreMessages, StoreFiles;
	
	protected $standOrders;

	/**
	 * Create a new controller instance.
	 *
	 * @param StandOrderRepository $standOrders
	 */
	public function __construct(StandDistrRepository $standOrders)
	{
		$this->middleware('auth');
		$this->standOrders = $standOrders;
	}

	/**
	 * Show the shop index page
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$this->standOrders->trackFilter();
        $this->standOrders->applyFilter(new StandOrderDistributorFilter());
	//	$this->standOrders->pushFilter(new BelongsUserFilter);
		$this->standOrders->pushFilter(new OrderDateFilter);
		$this->standOrders->pushTrackFilter(ScSearchFilter::class);

		return view('site::stand_distr.index', [
			'standOrders' => $this->standOrders->paginate(config('site.per_page.order', 80)),
			'repository' => $this->standOrders,
		]);
	}

	/**
	 * @param \ServiceBoiler\Prf\Site\Http\Requests\MessageRequest $request
	 * @param \ServiceBoiler\Prf\Site\Models\StandOrder $order
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function message(MessageRequest $request, StandOrder $standOrder)
	{   
        return $this->storeMessage($request, $standOrder);
	}

	/**
	 * @return \Illuminate\Http\Response
	 */
	
	public function show(StandOrder $standOrder)
	{
		$statuses = $standOrder->statuses()->get();
        return view('site::stand_distr.show', compact('standOrder','statuses'));
	}

	/**
	 * @param StandOrderRequest $request
	 * @param  StandOrder $standOrder
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(StandOrderRequest $request, StandOrder $standOrder)
	{
        if(!empty($request->input('standOrder'))) {
        $standOrder->update(['status_id' => $request->input('standOrder.status_id')]);
        $standOrder->fill($request->input('standOrder'));
        $receiver_id = $request->user()->getKey();
        $text='Сменен статус на ' . $standOrder->status->name;
        $standOrder->messages()->save($request->user()->outbox()->create(compact('text', 'receiver_id')));
		}
       
        $status_changed = $standOrder->isDirty('status_id');
        
		//if($request->input('standOrder.status_id') == 8) {
	//		event(new StandOrderUserConfirmEvent($standOrder));
		//}
        
		if ($request->input('standOrder.status_id') == 4 || $request->input('standOrder.status_id') == 5) {
			event(new StandOrderStatusChangeEvent($standOrder));
		}
		
        if ($request->input('standOrder.status_id') == 3) {
        	event(new StandOrderPaymentEvent($standOrder));
		}
        
		
        return redirect()->route('stand-distr.show', $standOrder)->with('success', trans('site::stand_order.updated'));

	}
    
    public function payment(FileRequest $request, StandOrder $standOrder)
	{   
        $standOrder->detachFiles();
		return $this->storeFile($request, $standOrder);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  StandOrder $order
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Exception
	 */
	public function destroy(StandOrder $order)
	{

		$this->authorize('delete', $order);

		if ($order->delete()) {
			$redirect = route('orders.index');
		} else {
			$redirect = route('orders.show', $order);
		}
		$json['redirect'] = $redirect;

		return response()->json($json);

	}

}