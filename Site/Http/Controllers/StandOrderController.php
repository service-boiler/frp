<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Concerns\StoreFiles;
use ServiceBoiler\Prf\Site\Events\StandOrderCreateEvent;
use ServiceBoiler\Prf\Site\Events\StandOrderUserConfirmEvent;
use ServiceBoiler\Prf\Site\Events\StandOrderStatusChangeEvent;
use ServiceBoiler\Prf\Site\Events\StandOrderFilesEvent;
use ServiceBoiler\Prf\Site\Filters\BelongsUserFilter;
use ServiceBoiler\Prf\Site\Filters\StandOrder\StandOrderAddressSelectFilter;
use ServiceBoiler\Prf\Site\Filters\StandOrder\StandOrderAddressFilter;
use ServiceBoiler\Prf\Site\Filters\OrderDateFilter;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\StandOrderLoadRequest;
use ServiceBoiler\Prf\Site\Http\Requests\StandOrderRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\StandOrder;
use ServiceBoiler\Prf\Site\Models\StandOrderItem;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Repositories\StandOrderRepository;
use ServiceBoiler\Prf\Site\Repositories\FileRepository;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use ServiceBoiler\Prf\Site\Support\StandOrderLoadFilter;

class StandOrderController extends Controller
{

	use  AuthorizesRequests, StoreMessages;
	/**
	 * @var StandOrderRepository
	 */
	protected $standOrders;

	/**
	 * Create a new controller instance.
	 *
	 * @param StandOrderRepository $standOrders
	 */
	public function __construct(StandOrderRepository $standOrders)
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
        $this->standOrders->applyFilter(new StandOrderAddressFilter());

		return view('site::stand_order.index', [
			'standOrders' => $this->standOrders->paginate(config('site.per_page.order', 8)),
			'repository' => $this->standOrders,
		]);
	}

    private function getParts(StandOrderRequest $request, StandOrder $standOrder = null)
    {
        $parts = collect([]);
        $old = $request->old('count');
        if (!is_null($old) && is_array($old)) {

            foreach ($old as $product_id => $count) {
                $product = Product::query()->findOrFail($product_id);
                $parts->put($product->id, collect([
                    'product' => $product,
                    'count'   => $count,
                    'cost' => $product->hasPrice ? $product->standOrderPrice->value : 0
                ]));
            }
        } elseif (!is_null($standOrder)) {
            foreach ($standOrder->parts as $part) {
                $parts->put($part->product_id, collect([
                    'product' => $part->product,
                    'count'      => $part->count,
                    'cost' => $part->product->hasPrice ? $part->product->standOrderPrice->value : 0
                ]));
            }
        }
        return $parts;
    }
    
       private function setFiles(StandOrderRequest $request, StandOrder $standOrder)
    {
        //$standOrder->detachFiles();

        if ($request->filled('file')) {
            foreach ($request->input('file') as $type_id => $values) {
                foreach ($values as $file_id) {
                    $standOrder->files()->save(File::find($file_id));
                }
            }
        }
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  StandOrderRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(StandOrderRequest $request)
	{    
        //$this->authorize('create', StandOrder::class);
        $request->user()->standOrders()->save($standOrder = $this->standOrders->create($request->input(['standOrder'])));
        if ($request->filled('count')) {
            $parts = (collect($request->input('count')))->map(function ($count, $product_id) {
                $product = Product::query()->findOrFail($product_id);
                return new StandOrderItem([
                    'product_id' => $product_id,
                    'quantity'=> $count,
                    'price' => $product->hasPrice ? $product->standOrderPrice->price : 0,
                    'currency_id' => $product->hasPrice ? $product->standOrderPrice->currency_id : 978,
                    
                ]);
            });
            
            $standOrder->items()->saveMany($parts);
        }
        
        $standOrder->messages()->save($request->user()->outbox()->create($request->message));
        
        event(new StandOrderCreateEvent($standOrder));
        
        return redirect()->route('stand-orders.index')->with('success', trans('site::stand_order.created'));
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
	public function create(StandOrderRequest $request)
	{
        $parts = $this->getParts($request);
        $addresses =  $request->user()->addresses()->where('type_id',2)->get();
        $contragents = $request->user()->contragents()->orderBy('name')->get();
        $warehouses = $request->user()->distrWarehouses('product_group_types',1);
        $user = $request->user();
        
        
        
		return view('site::stand_order.create', compact('parts','addresses','contragents','warehouses','user'));
	}

	
	public function show(StandOrder $standOrder)
	{
		
        $statuses = $standOrder->statuses()->get();
        $this->authorize('view', $standOrder);
        $warehouses = Address::query()->where('type_id',6)->orderBy('name')->get();
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', config('site.stand_order_group_file'))->orderBy('sort_order')->get();
        $files = $standOrder->files()->get();

		return view('site::stand_order.show', compact('standOrder','warehouses','file_types','files','statuses'));
	}

	/**
	 * @param StandOrderRequest $request
	 * @param  StandOrder $standOrder
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(StandOrderRequest $request, StandOrder $standOrder)
	{
        
		//$standOrder->update($request->input('standOrder'));
        
        if(!empty($request->input('standOrder'))) {
        $standOrder->fill($request->input('standOrder'));
		}
		
        $standOrder->save();
        
        if(!empty($request->input('standOrder.status_id'))) {
        $receiver_id = $request->user()->getKey();
        $text='Сменен статус на ' . $standOrder->status->name;
        $standOrder->messages()->save($request->user()->outbox()->create(compact('text', 'receiver_id')));
			event(new StandOrderStatusChangeEvent($standOrder));
		}
        
        $this->setFiles($request, $standOrder);
		
        if(!empty($request->input('file'))) {
        event(new StandOrderFilesEvent($standOrder));
        }
        
        return redirect()->route('stand-orders.show', $standOrder)->with('success', trans('site::stand_order.updated'));

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