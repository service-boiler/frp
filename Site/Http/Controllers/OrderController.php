<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Events\OrderCreateEvent;
use ServiceBoiler\Prf\Site\Events\OrderUserConfirmEvent;
use ServiceBoiler\Prf\Site\Facades\Cart;
use ServiceBoiler\Prf\Site\Filters\BelongsUserFilter;
use ServiceBoiler\Prf\Site\Filters\Order\OrderAddressSelectFilter;
use ServiceBoiler\Prf\Site\Filters\OrderDateFilter;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\OrderLoadRequest;
use ServiceBoiler\Prf\Site\Http\Requests\OrderRequest;
use ServiceBoiler\Prf\Site\Models\Order;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Repositories\OrderRepository;
use ServiceBoiler\Prf\Site\Support\OrderLoadFilter;

class OrderController extends Controller
{

	use  AuthorizesRequests, StoreMessages;
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
		$this->middleware('auth');
		$this->orders = $orders;
	}

	/**
	 * Show the shop index page
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$this->orders->trackFilter();
		$this->orders->pushFilter(new BelongsUserFilter);
		$this->orders->pushFilter(new OrderDateFilter);
		$this->orders->pushTrackFilter(OrderAddressSelectFilter::class);

		return view('site::order.index', [
			'orders' => $this->orders->paginate(config('site.per_page.order', 8)),
			'repository' => $this->orders,
		]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  OrderRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(OrderRequest $request)
	{
		$request->createOrder();

		return redirect()->route('orders.index')->with('success', trans('site::order.created'));
	}

	/**
	 * @param \ServiceBoiler\Prf\Site\Http\Requests\MessageRequest $request
	 * @param \ServiceBoiler\Prf\Site\Models\Order $order
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function message(MessageRequest $request, Order $order)
	{
		return $this->storeMessage($request, $order);
	}

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('site::order.create');
	}

	/**
	 * @param OrderLoadRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
	 */
	public function load(OrderLoadRequest $request)
	{
		$inputFileType = ucfirst($request->path->getClientOriginalExtension());
		$filterSubset = new OrderLoadFilter();
		/** @var BaseReader $reader */
		$reader = IOFactory::createReader($inputFileType);
		$reader->setReadDataOnly(true);
		$reader->setReadFilter($filterSubset);

		$spreadsheet = $reader->load($request->path->getPathname());

		$rowIterator = $spreadsheet->getActiveSheet()->getRowIterator();

		$data = [];

		foreach ($rowIterator as $r => $row) {

			$cellIterator = $row->getCellIterator();

			foreach ($cellIterator as $c => $cell) {


				switch ($c) {
					case 'A':
						$sku = (string)trim($cell->getValue());
						/** @var Product $product */
						$product = Product::query()->where('sku', $sku)->firstOrFail();

						break;
					case 'B':

						$quantity = (int)$cell->getValue();

						break;
				}
			}
			Cart::add(array_merge($product->toCart(), compact('quantity')));
		}

		return redirect()->route('orders.create')->with('success', trans('site::order.loaded'));


	}

	/**
	 * Display the specified resource.
	 *
	 * @param Order $order
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function show(Order $order)
	{
		$this->authorize('view', $order);

		return view('site::order.show', compact('order'));
	}

	/**
	 * @param OrderRequest $request
	 * @param  Order $order
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(OrderRequest $request, Order $order)
	{

		$order->update($request->input('order'));

		if($request->input('order.status_id') == 8) {
			event(new OrderUserConfirmEvent($order));
		}

		return redirect()->route('orders.show', $order)->with('success', trans('site::order.confirmed'));

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Order $order
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Exception
	 */
	public function destroy(Order $order)
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