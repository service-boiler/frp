<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreFiles;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Events\OrderStatusChangeEvent;
use ServiceBoiler\Prf\Site\Filters\Order\DistributorFilter;
use ServiceBoiler\Prf\Site\Http\Requests\FileRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Models\Order;
use ServiceBoiler\Prf\Site\Repositories\OrderRepository;

class DistributorController extends Controller
{

    use AuthorizesRequests, StoreMessages, StoreFiles;

    /**
     * @var OrderRepository
     */
    protected $orders;

    /**
     * Create a new controller instance.
     *
     * @param OrderRepository $orders
     */
    public function __construct(OrderRepository $orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->orders->trackFilter();
        $this->orders->applyFilter(new DistributorFilter());


        return view('site::distributor.index', [
            'orders'     => $this->orders->paginate(config('site.per_page.order', 8)),
            'repository' => $this->orders,
        ]);
    }

	/**
	 * @param Order $order
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function show(Order $order)
    {

        $this->authorize('distributor', $order);
	    $file = $order->file;

        return view('site::distributor.show', compact('order', 'file'));
    }

	/**
	 * @param FileRequest $request
	 * @param Order $order
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function payment(FileRequest $request, Order $order)
	{
		return $this->storeFile($request, $order);
	}

    /**
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Order $order)
    {
        $order->fill($request->input('order'));
        $status_changed = $order->isDirty('status_id');
        $order->save();
        if ($status_changed) {
            event(new OrderStatusChangeEvent($order));
        }

        return redirect()->route('distributors.show', $order)->with('success', trans('site::order.updated'));
    }

    /**
     * @param \ServiceBoiler\Prf\Site\Http\Requests\MessageRequest $request
     * @param \ServiceBoiler\Prf\Site\Models\Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function message(MessageRequest $request, Order $order)
    {
        return $this->storeMessage($request, $order);
    }

}