<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Exports\Excel\EsbUserProductsExcel;
use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\Region\SelectFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SortFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductAddressSearchFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductOwnerFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductOwnerArchiveFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductEnabledFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductNotEnabledFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductSearchSerialFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductUserSearchFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductProductSearchFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductMaintenanceNeedSelectFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductMaintenanceDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductMaintenanceDateToFilter;
use ServiceBoiler\Prf\Site\Http\Requests\ImageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\ServiceIdRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\Contragent;
use ServiceBoiler\Prf\Site\Models\Equipment;
use ServiceBoiler\Prf\Site\Models\EsbAdoContract;
use ServiceBoiler\Prf\Site\Models\EsbUserProduct;
use ServiceBoiler\Prf\Site\Models\EsbUserVisitType;
use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Models\Image;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;
use ServiceBoiler\Prf\Site\Repositories\EsbUserProductRepository;

class EsbUserProductController extends Controller
{

    use AuthorizesRequests, ValidatesRequests, StoreMessages;
    
    public function __construct(RegionRepository $regions, EsbUserProductRepository $esbUserProducts)
    {
        $this->regions = $regions;
        $this->esbUserProducts = $esbUserProducts;
    }
    
    public function index(Request $request)
    {
        $this->esbUserProducts->trackFilter();
        $this->esbUserProducts->applyFilter(new EsbUserProductOwnerFilter());
        $this->esbUserProducts->applyFilter(new EsbUserProductEnabledFilter());
		$this->esbUserProducts->pushTrackFilter(EsbUserProductUserSearchFilter::class);
		$this->esbUserProducts->pushTrackFilter(EsbUserProductAddressSearchFilter::class);
		$this->esbUserProducts->pushTrackFilter(EsbUserProductSearchSerialFilter::class);
		$this->esbUserProducts->pushTrackFilter(EsbUserProductProductSearchFilter::class);
		$this->esbUserProducts->pushTrackFilter(EsbUserProductMaintenanceNeedSelectFilter::class);
		$this->esbUserProducts->pushTrackFilter(EsbUserProductMaintenanceDateFromFilter::class);
		$this->esbUserProducts->pushTrackFilter(EsbUserProductMaintenanceDateToFilter::class);
		//$this->esbUserProducts->pushTrackFilter(ProductGroupTypeFilter::class);
       if ($request->has('excel')) {
			(new EsbUserProductsExcel())->setRepository($this->esbUserProducts)->render();
		}
        return view('site::esb_user_product.index',[
            'repository' => $this->esbUserProducts,
            'esbUserProducts'    => $this->esbUserProducts->paginate($request->input('filter.per_page', 100), ['esb_user_products.*'])
        ]);
    }
    public function indexArchive(Request $request)
    {
        $this->esbUserProducts->trackFilter();
        $this->esbUserProducts->applyFilter(new EsbUserProductOwnerArchiveFilter());
     
        $this->esbUserProducts->pushTrackFilter(EsbUserProductUserSearchFilter::class);
		$this->esbUserProducts->pushTrackFilter(EsbUserProductAddressSearchFilter::class);
		$this->esbUserProducts->pushTrackFilter(EsbUserProductSearchSerialFilter::class);
		$this->esbUserProducts->pushTrackFilter(EsbUserProductProductSearchFilter::class);
		//$this->esbUserProducts->pushTrackFilter(ProductGroupTypeFilter::class);
       
        return view('site::esb_user_product.index_archive',[
            'repository' => $this->esbUserProducts,
            'esbUserProducts'    => $this->esbUserProducts->paginate($request->input('filter.per_page', 100), ['esb_user_products.*'])
        ]);
    }
    
    
    public function show(EsbUserProduct $esbUserProduct)
    {
        $launch=$esbUserProduct->launches->first();
        $user=auth()->user();
        $engineers=$user->childEngineers;
        $types=EsbUserVisitType::where('enabled',1)->orderBy('sort_order')->get();
        $planned_visits = $esbUserProduct->visits()->whereIn('status_id',[1,2,3,5,6])->get();
        $file_types_ado = FileType::query()->where('enabled', 1)->where('group_id', 17)->orderBy('sort_order')->get();
        $files_ado = $esbUserProduct->esbAdoContract ? $esbUserProduct->esbAdoContract->files()->get() : collect([]);
        
        if($esbUserProduct->esbUser->esbRequests()->where('status_id','!=',7)->where('recipient_id',$user->id)->exists()
                 || $esbUserProduct->esbUser->esbServices()->wherePivot('enabled',1)->get()->contains($user->id) 
                 || $esbUserProduct->esbUser->id == $user->id 
                 || $user->admin
                 || $user->hasPermission('admin_repairs')
                 || $user->hasPermission('admin_users_view')
                 ) {
                
                return view('site::esb_user_product.show', compact('esbUserProduct','launch','types','engineers','planned_visits','file_types_ado','files_ado'));
                    
        } else {
            return view('site::esb_user_product.show_sm', compact('esbUserProduct','launch','types','engineers','planned_visits','file_types_ado','files_ado'));;
        }
        
        
    }
    

    public function manage(EsbUserProduct $esbUserProduct)
    {
        return view('site::esb_user_product.manage', compact('esbUserProduct'));
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
        // if($esbUserProduct->launches()->exists()){
            // return redirect()->route('esb-user-products.show',$esbUserProduct)->with('error', trans('site::user.esb_user_product.edin_denied_launch_exists'));
        
        // }
        
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
        if($user->hasRole(config('site.supervisor_roles'),[])){
            $user=User::find($request->user_id);
        }

        $user->esbProducts()->save($esbUserProduct = EsbUserProduct::query()->create($request->only(
            'product_id', 
            'serial', 
            'address_id' ,
            'date_sale' ,
            'product_no_cat' ,
            'sale_comment' 
            ))); 

        return redirect()->route('esb-user-products.show',$esbUserProduct)->with('success', trans('site::user.esb_user_product.success'));
        
    }
    
    public function update(Request $request, EsbUserProduct $esbUserProduct)
    {   
        $user=Auth()->user();
        if($user->type_id!=4) {
            $esbClaim = $esbUserProduct->esbClaim()->updateOrCreate(['esb_user_product_id'=>$esbUserProduct->id],$request->input('esbClaim'));
        }
        
        if($request->create_ado_contract==1){
            $contragent=Contragent::where('inn',$request->service_inn)->first();
            $esbAdoContract=$esbUserProduct->esbAdoContract()->updateOrCreate([
                'esb_user_product_id'=>$esbUserProduct->id,],[
                'valid_to'=>$request->valid_to,
                'contract_number'=>$request->contract_number,
                'service_name'=>$request->service_name,
                'service_inn'=>$request->service_inn,
                'service_phone'=>$request->service_phone,
                'contragent_id'=>$contragent ? $contragent->id : null,
                'service_id'=>$contragent ? $contragent->user->id : null,
            ]);
                     
            
            $this->setFilesAdo($request, $esbAdoContract);
            return redirect()->route('esb-user-products.show',$esbUserProduct)->with('success', trans('site::user.esb_user_product.update_success'));
        }
        
        
        if($esbUserProduct->launches()->exists()){
          $esbUserProduct->update($request->only(
            'product_no_cat' , 'enabled'
            )); 
            if($request->enabled){
                 return redirect()->route('esb-user-products.show',$esbUserProduct)->with('success', trans('site::user.esb_user_product.update_success'));
            } else {
                return redirect()->route('esb-user-products.show',$esbUserProduct)->with('error', trans('site::user.esb_user_product.edin_denied_launch_exists'));
            }
         } else {
        $esbUserProduct->update($request->only(
            'product_id', 
            'serial', 
            'address_id' ,
            'date_sale' ,
            'product_no_cat' ,
            'sale_comment', 'enabled'
            ));
        
        return redirect()->route('esb-user-products.show',$esbUserProduct)->with('success', trans('site::user.esb_user_product.update_success'));
        }
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
    
    private function setFilesAdo($request, EsbAdoContract $esbAdoContract)
    {
        $esbAdoContract->detachFiles();

        if ($request->filled('file')) {
            foreach ($request->input('file') as $type_id => $values) {
                foreach ($values as $file_id) {
                    $esbAdoContract->files()->save(File::find($file_id));
                }
            }
        }
        //$this->files->deleteLostFiles();
    }
}
