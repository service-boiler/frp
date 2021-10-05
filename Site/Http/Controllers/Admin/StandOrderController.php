<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreFiles;
use ServiceBoiler\Prf\Site\Events\StandOrderScheduleEvent;
use ServiceBoiler\Prf\Site\Events\StandOrderStatusChangeEvent;
use ServiceBoiler\Prf\Site\Exports\Excel\StandOrdersExcel;
use ServiceBoiler\Prf\Site\Filters\StandOrder\StandOrderAddressSelectFilter;
use ServiceBoiler\Prf\Site\Filters\StandOrder\FerroliManagersStandOrdersFilter;
use ServiceBoiler\Prf\Site\Filters\StandOrder\StandOrderUserSelectFilter;
use ServiceBoiler\Prf\Site\Filters\StandOrder\ScSearchFilter;
use ServiceBoiler\Prf\Site\Http\Requests\FileRequest;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\StandOrderRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\StandOrder;
use ServiceBoiler\Prf\Site\Models\StandOrderItem;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\StandOrderRepository as Repository;
use ServiceBoiler\Prf\Site\Repositories\FileRepository;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use ServiceBoiler\Prf\Site\Support\CommentBox;

class StandOrderController extends Controller
{

	use AuthorizesRequests, StoreFiles;
	/**
	 * @var Repository
	 */
	protected $standOrders;
	/**
     * @var FileTypeRepository
     */
    protected $types;
    /**
     * @var FileRepository
     */
    protected $files;

	/**
	 * Create a new controller instance.
	 *
	 * @param Repository $standOrders
	 */
	public function __construct(
        Repository $standOrders,
        FileRepository $files,
        FileTypeRepository $types)
	{
		$this->middleware('auth');
		$this->standOrders = $standOrders;
        $this->types = $types;
        $this->files = $files;
	}

	/**
	 * Show the shop index page
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	public function index(Request $request)
	{
		$this->standOrders->trackFilter();
        $this->standOrders->applyFilter(new FerroliManagersStandOrdersFilter);
		$this->standOrders->pushTrackFilter(ScSearchFilter::class);
		$this->standOrders->pushTrackFilter(StandOrderAddressSelectFilter::class);
		
		if ($request->has('excel')) {
			(new StandOrdersExcel())->setRepository($this->standOrders)->render();
		}

		return view('site::admin.stand_order.index', [
			'standOrders' => $this->standOrders->paginate($request->input('filter.per_page', config('site.per_page.order', 100)), ['stand_orders.*']),
			'repository' => $this->standOrders,
		]);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  StandOrder $standOrder
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(StandOrder $standOrder)
	{
		$file = $standOrder->file;
        $statuses = $standOrder->statuses()->get();
        $warehouses = Address::query()->where('type_id',6)->orderBy('name')->get();
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', config('site.stand_order_group_file'))->orderBy('sort_order')->get();
        $files = $standOrder->files()->get();
        $commentBox = new CommentBox($standOrder);

		return view('site::admin.stand_order.show', compact('standOrder', 'file', 'file_types', 'files','warehouses','statuses','commentBox'));
	}

	public function create(StandOrderRequest $request, User $user)
	{
        $parts = $this->getParts($request);
        $addresses =  $user->addresses()->where('type_id',2)->get();
        $contragents = $user->contragents()->orderBy('name')->get();
        $warehouses = $user->distrWarehouses('product_group_types',1);
        
		return view('site::admin.stand_order.create', compact('parts','addresses','contragents','warehouses','user'));
	}
    
	public function user()
	{
        return view('site::admin.stand_order.user');
	}
    
	public function store(StandOrderRequest $request)
	{    
        //$this->authorize('create', StandOrder::class);
        
        
        $user=User::find($request->input('standOrder.user_id'));
        
        $user->standOrders()->save($standOrder = $this->standOrders->create($request->input(['standOrder'])));
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
        
        //event(new StandOrderCreateEvent($standOrder));
        
        return redirect()->route('admin.stand-orders.index')->with('success', trans('site::stand_order.created'));
    }
    
	public function print(StandOrder $standOrder)
	{
		$file = $standOrder->file;
        $warehouses = Address::query()->where('type_id',6)->orderBy('name')->get();
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', config('site.stand_order_group_file'))->orderBy('sort_order')->get();
        $files = $standOrder->files()->get();

		return view('site::admin.stand_order.print', compact('standOrder', 'file', 'file_types', 'files','warehouses'));
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
	/**
	 * @param FileRequest $request
	 * @param StandOrder $standOrder
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function payment(FileRequest $request, StandOrder $standOrder)
	{
		return $this->storeFile($request, $standOrder);
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
	 * @param StandOrder $standOrder
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function schedule(StandOrder $standOrder)
	{
		$this->authorize('schedule', $standOrder);
		event(new StandOrderScheduleEvent($standOrder));

		return redirect()->route('admin.stand-orders.show', $standOrder)->with('success', trans('site::schedule.created'));

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
        $standOrder->fill($request->input('standOrder'));
		}
        
        $status_changed = $standOrder->isDirty('status_id');
        
        if($standOrder->status_id == '10') {
            $standOrder->update(['warehouse_address_id' => '1']);
		}
        
        $standOrder->save();
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
        $this->setFiles($request, $standOrder);

		if ($status_changed) {
        
            $text='Сменен статус на ' . $standOrder->status->name;
            $receiver_id = $request->user()->getKey();
            $standOrder->messages()->save($request->user()->outbox()->create(['text'=>$text, 'receiver_id'=>$receiver_id, 'personal'=>'1']));
			event(new StandOrderStatusChangeEvent($standOrder));
		}
        
		return redirect()->route('admin.stand-orders.show', $standOrder)->with('success', trans('site::order.updated'));

	}
    
	public function order(StandOrder $standOrder)
    {
        
        $order_products=[];
       
        foreach($standOrder->items as $item) {
            $order_products[$item->product_id]=['product_id'=>$item->product_id,'quantity'=>$item->quantity,'price'=>round($item->cost,0),'currency_id'=>$item->currency_id];
        }
        
        $contragent=$standOrder->user->contragents()->orderByDesc('created_at')->first();
       
        $order = $standOrder->user->orders()->create(['status_id'=>'1','contragent_id'=>$contragent->id,'address_id'=>'1','in_stock_type'=>'0']);
        $order->items()->createMany($order_products);
        $standOrder->order_id=$order->id;
        $standOrder->save();
        
        return redirect()->route('admin.stand-orders.show', $standOrder)->with('success', 'Заказ для витрины создан. Его необходимо отправить в 1С. Перейдите в заказ.');
        
    }
    
	public function message(MessageRequest $request, StandOrder $standOrder)
	{
		$standOrder->messages()->save($request->user()->outbox()->create($request->input('message')));

		return redirect()->route('admin.stand-orders.show', $standOrder)->with('success', trans('site::message.created'));
	}

}