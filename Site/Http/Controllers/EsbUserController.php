<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\Region\SelectFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SortFilter;
use ServiceBoiler\Prf\Site\Filters\User\EsbUserServiceFilter;
use ServiceBoiler\Prf\Site\Http\Requests\ImageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\ServiceIdRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\Equipment;
use ServiceBoiler\Prf\Site\Models\EsbUserProduct;
use ServiceBoiler\Prf\Site\Models\Image;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;

class EsbUserController extends Controller
{

    use AuthorizesRequests, ValidatesRequests, StoreMessages;
    
    public function __construct(RegionRepository $regions, UserRepository $users)
    {
        $this->users = $users;
        $this->regions = $regions;
    }
    
    public function index(Request $request)
    {
        $this->users->trackFilter();
        $this->users->applyFilter(new EsbUserServiceFilter());
        
        return view('site::esb_user.index',[
            'repository' => $this->users,
            'users'    => $this->users->paginate($request->input('filter.per_page', 100), ['users.*'])
        ]);
    }
    
    public function show(User $esbUser)
    {
        return view('site::esb_user.show', compact('esbUser'));
    }
    
    
    public function create(Request $request)
    {   
        $user=Auth()->user();
        $addresses=$user->addressesActual;
        $equipments = Equipment::query()
            ->where(config('site.check_field'), 1)
            ->where('enabled', 1)
            ->where('catalog_id','!=','52')
            ->whereHas('products', function ($product) {
                $product
                    ->where(config('site.check_field'), 1)
                    ->where('enabled', 1);
            })
            ->whereHas('catalog', function ($catalog) {
                $catalog
                    ->where(config('site.check_field'), 1)
                    ->where('enabled', 1);
            })
            ->orderBy('name')
            ->get();
        $products = collect([]);
        
        $equipments_archive = Equipment::query()
            ->where('enabled', 1)
            ->whereHas('products', function ($product) {
                $product
                    ->where('enabled', 1);
            })
            ->whereHas('catalog', function ($catalog) {
                $catalog
                    ->where('enabled', 1);
            })
            ->orderBy('name')
            ->get();
        $products = collect([]);

        if(old('equipment_id')){
            $products = Product::query()
                ->where('equipment_id', old('equipment_id'))
                ->mounter()
                ->get();
        }

        return view('site::esb_user_product.create', compact('equipments','products', 'user','addresses'));
        
    }
     
    public function edit(EsbUserProduct $esbUserProduct)
    {   
        $user=Auth()->user();
        $addresses=$user->addressesActual;
        $equipments = Equipment::query()
            ->where(config('site.check_field'), 1)
            ->where('enabled', 1)
            ->where('catalog_id','!=','52')
            ->whereHas('products', function ($product) {
                $product
                    ->where(config('site.check_field'), 1)
                    ->where('enabled', 1);
            })
            ->whereHas('catalog', function ($catalog) {
                $catalog
                    ->where(config('site.check_field'), 1)
                    ->where('enabled', 1);
            })
            ->orderBy('name')
            ->get();
        $products = collect([]);
        
        if($esbUserProduct->product) {
            $products = $esbUserProduct->product->equipment->products;
        
        }
        
        $equipments_archive = Equipment::query()
            ->where('enabled', 1)
            ->whereHas('products', function ($product) {
                $product
                    ->where('enabled', 1);
            })
            ->whereHas('catalog', function ($catalog) {
                $catalog
                    ->where('enabled', 1);
            })
            ->orderBy('name')
            ->get();
      

        if(old('equipment_id')){
            $products = Product::query()
                ->where('equipment_id', old('equipment_id'))
                ->mounter()
                ->get();
        }

        return view('site::esb_user_product.edit', compact('esbUserProduct','equipments','products', 'user','addresses'));
        
    }
    
    
    public function store(Request $request)
    {   
        $user=Auth()->user();
        
        $user->esbProducts()->save($esbUserProduct = EsbUserProduct::query()->create($request->only(
            'product_id', 
            'serial', 
            'address_id' ,
            'date_sale' ,
            'product_no_cat' ,
            'sale_comment' 
            )));
        
        return redirect()->route('home')->with('success', trans('site::user.esb_user_product.success'));
        
    }
    
    public function update(Request $request, EsbUserProduct $esbUserProduct)
    {   
        $user=Auth()->user();
        $esbUserProduct->update($request->only(
            'product_id', 
            'serial', 
            'address_id' ,
            'date_sale' ,
            'product_no_cat' ,
            'sale_comment' 
            ));
        
        return redirect()->route('home')->with('success', trans('site::user.esb_user_product.success'));
        
    }
    
    public function destroy(EsbUserProduct $EsbUserProduct)
	{

		//$this->authorize('delete', $order);
        $EsbUserProduct->enabled=0;
        $EsbUserProduct->save();
        
		$redirect = route('home');

		$json['redirect'] = $redirect;

		return response()->json($json);

	}
    
    public function message(MessageRequest $request)
    {
        return $this->storeMessage($request, Auth::user());
    }
}