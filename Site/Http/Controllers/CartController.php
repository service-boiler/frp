<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;
use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Http\Resources\Address\DealerCollection;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Fomvasss\Dadata\Facades\DadataSuggest;
use ServiceBoiler\Prf\Site\Models\Order;
use Stevebauman\Location\Facades\Location;
use ServiceBoiler\Prf\Site\Facades\Cart;
use ServiceBoiler\Prf\Site\Filters\Address\AddressIsApprovedFilter;
use ServiceBoiler\Prf\Site\Filters\Address\AddressIsDealerFilter;
use ServiceBoiler\Prf\Site\Filters\Address\AddressMapFilter;
use ServiceBoiler\Prf\Site\Filters\Address\RegionSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Address\RegionFilter;
use ServiceBoiler\Prf\Site\Filters\Address\AddressShowMarketFilter;
use ServiceBoiler\Prf\Site\Filters\Region\RegionDealerMapFilter;
use ServiceBoiler\Prf\Site\Http\Requests\CartItemRequest;
use ServiceBoiler\Prf\Site\Http\Requests\OrderRetailRequest;
use ServiceBoiler\Prf\Site\Http\Requests\UserRegionRequest;
use ServiceBoiler\Prf\Site\Mail\RetailOrder\RetailOrderCreateEmail;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\AuthorizationType;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\ProductGroupType;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\Variable;
use ServiceBoiler\Prf\Site\Repositories\ContragentRepository;
use ServiceBoiler\Prf\Site\Repositories\AddressRepository;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;


class CartController extends Controller
{
    /**
     * @var ContragentRepository
     */
    private $authorization_types;
    private $contragents;

    /**
     * CartController constructor.
     * @param ContragentRepository $contragents
     */
    public function __construct(AddressRepository $addresses, ContragentRepository $contragents, RegionRepository $regions)
    {
        $this->addresses = $addresses;
		$this->regions = $regions;
		$this->contragents = $contragents;
		$this->authorization_types = AuthorizationType::query()
            ->where('brand_id', config('site.brand_default'))
            ->where('enabled', 1)
            ->orderBy('name')
            ->get();

    }

    /**
     * Show the shop index page
     *
     * @param CartItemRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(CartItemRequest $request)
    {	
        $user = $request->user();
        if(!empty($user)){
        $contragents = $request->user()->contragents;
            $warehouses = $request->user()->warehousesCart();
        }
        $static_currency = Variable::whereName('currency_static_euro')->first()->value;	
        $productGroupTypes = ProductGroupType::all();
        $ip = request()->ip();
        if(env('APP_DEBUG')){
            $ip='31.40.129.210';
        }
    	$region_code_geoip='RU-MOW';
		if(empty($request->session()->get('user_region_id'))) {
            $result = DadataSuggest::iplocate($ip);
            
            if($result['data']['region_iso_code']=='UA-43')
                {
                    $result['data']['region_iso_code']='RU-KRM';
                }

            if(!empty($result['value'])){
                $region_id=$result['data']['region_iso_code'];
                
            } 
            
           // $location=Location::get($ip);
           // $region_id = $location->countryCode ."-" .$location->regionCode; 
          //  if($location->countryCode != 'RU') {
            //        $region_id = "RU-MOW";
            //    }
        }
		else {
            $region_id = $request->session()->get('user_region_id');
		}
        
        $this->addresses->applyFilter((new RegionFilter())->setRegionId($region_id));
		
		$regions = $this->regions
            ->trackFilter()
            ->applyFilter(new RegionDealerMapFilter())
            ->all();

		$addresses =  $this->addresses
                ->trackFilter()
                ->pushTrackFilter(RegionSelectFilter::class)
                ->applyFilter(new AddressIsApprovedFilter())
                ->applyFilter(new AddressIsDealerFilter())
                ->applyFilter(new AddressShowMarketFilter())
                ->applyFilter(
                    (new AddressMapFilter())
                        ->setAccepts($request->input(
                            'filter.authorization_type',
                            $this->authorization_types->pluck('id')->toArray()
                        ))
                        ->setRoleId(4)
                )
                ->applyFilter((new RegionFilter())->setRegionId($region_id))
				->all();
		
        $user_cart_address_id = $request->session()->get('user_cart_address_id');
		
        if(!empty($request->session()->get('user_cart_address_id'))) {
        $cart_address = $this->addresses->find($request->session()->get('user_cart_address_id'));
        } else {
        $cart_address = null;
        }
        
        $dealers=collect();
        foreach($addresses->groupBy('addressable_id') as $key=>$dealer) {
        
            $dealers=$dealers->push(['dealer' => User::find($key), 'addresses' => $dealer]);
        
        }
     

        return view('site::cart.index', compact('contragents', 'warehouses', 'productGroupTypes','user','static_currency'));


    }
	
	public function suess($ordernum)
    {
	//$this->$ordernum = $ordernum;
	return view('site::cart.suess', compact('ordernum'));
	}

	public function createOrder(Request $request)
    {
        $cart_products = Cart::toArray($request->input('products', []));

        if(!$user = User::query()->where('phone', clearPhone($request->input('order.phone')))->first()) {
            $user=User::create([
                'email'=>$request->input('order.email'),
                'name'=>$request->input('order.name'),
                'name_for_site'=>$request->input('order.name'),
                'type_id'=>'4',
                'phone'=>clearPhone($request->input('order.phone')),
                ]);


        }
        $order = $user->orders()->create(array_merge($request->input('order'), ['in_stock_type' => '1']));
        $order->items()->createMany($cart_products);

        return redirect()->route('orders.single_show',$order->site_guid);
    }

	public function showOrder($siteGuid)
    {
        $order=Order::query()->where('site_guid',$siteGuid)->first();
        return view('site::order.show_sm', compact('order'));
    }

	public function userRegionChange(UserRegionRequest $request)
    {	
		$request->session()->put('user_region_id',$request->input('filter.region_id'));
		$request->session()->forget('user_cart_address_id');
        
		return redirect('/cart#cart');
	
	}

	public function userMarketChange(UserRegionRequest $request)
    {	
		
        if(!empty($request->region_id)){
            $request->session()->put('user_region_id',$request->region_id);
        }
        
        if(!empty($request->address_id)){
            $request->session()->put('user_cart_address_id', $request->address_id);
        }
        
        if(Cart::isEmpty()) {
            return redirect(route('catalogs.index'));
        } else {
        return redirect('/cart#cart');
        }
	
	}
    /**
     * @param CartItemRequest $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(CartItemRequest $request, Product $product)
    {
        Cart::add(array_merge($product->toCart(), $request->only(['quantity'])));

        return response()->json([
            'replace' => [
                '.cart-nav' => view('site::cart.nav')->render(),
            ],
            'update'  => [
                '#cart-table'                      => view('site::cart.item.rows')->render(),
                '#confirm-add-to-cart .modal-body' => $request->input('name'),
            ]
        ]);
    }

    /**
     * @param CartItemRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CartItemRequest $request)
    {
        Cart::update($request->all());


        return response()->json([
            'replace' => [
                '#cart-item-' . $request->input('product_id') => view('site::cart.item.row')->with('item', Cart::get($request->input('product_id')))->render(),
                '.cart-nav'                                   => view('site::cart.nav')->render()
            ],
            'update'  => [
                '#cart-total'  => Cart::price_format(Cart::total()),
                '#cart-weight' => Cart::weight_format(),
            ]
        ]);
    }

    /**
     * @param CartItemRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(CartItemRequest $request)
    {
        Cart::remove($request->input('product_id'));

        return response()->json([
            'refresh' => Cart::isEmpty(),
            'replace' => [
                '#cart-item-' . $request->input('product_id') => '',
                '.cart-nav'                                   => view('site::cart.nav')->render()
            ],
            'update'  => [
                '#cart-total'  => Cart::price_format(Cart::total()),
                '#cart-weight' => Cart::weight_format(),
            ]
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        Cart::clear();

        return redirect()->route('cart');
    }
	
	
	public function orderRetail(OrderRetailRequest $request)
	{	
		$cart_products = Cart::toArray($request->input('products', []));
		$user = $request->user();
        $chain_guid = Str::uuid()->toString();
        foreach($request->input('recipient', []) as $address_id)
		{
		
			$address = Address::query()->find($address_id);
			$retailorder = $address->retailorders()->create(array_merge($request->input('order'),['esb_user_id'=>$user->id]));
			$retailorder->items()->createMany($cart_products);
            $retailorder->chain_guid=$chain_guid;
            $retailorder->save();
            
            Mail::to($address->email)
            ->send(new RetailOrderCreateEmail(
                "Новая заявка с сайта market.ferroli.ru [$retailorder->id]",
                $retailorder, $address
            ));
            
            Mail::to([env('MAIL_RETAIL_ORDER_ADDRESS'),$address->region->notification_address])
            ->send(new RetailOrderCreateEmail(
                "Новая заявка с сайта market.ferroli.ru [$retailorder->id]",
                $retailorder, $address
            ));
		
        }
		Cart::remove(array_keys($cart_products));
		
		return redirect()->route('cartsuess',$retailorder->id);
		
	}
	
	
}