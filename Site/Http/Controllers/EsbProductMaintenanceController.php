<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\ActiveFilter;
use ServiceBoiler\Prf\Site\Filters\ByIdDescSortFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserMaintenance\AddressSearchFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserMaintenance\SearchSerialFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserMaintenance\UserSearchFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserMaintenance\EsbMaintenanceOwnerFilter;
use ServiceBoiler\Prf\Site\Filters\FileType\ModelHasFilesFilter;
use ServiceBoiler\Prf\Site\Filters\FileType\EsbProductMaintenanceFilter;
use ServiceBoiler\Prf\Site\Filters\FileType\SortFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SelectFilter;
use ServiceBoiler\Prf\Site\Http\Requests\ImageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\ServiceIdRequest;
use ServiceBoiler\Prf\Site\Http\Requests\EsbProductMaintenanceRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\Equipment;
use ServiceBoiler\Prf\Site\Models\EsbUserProduct;
use ServiceBoiler\Prf\Site\Models\EsbProductMaintenance;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Models\Image;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\FileRepository;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;
use ServiceBoiler\Prf\Site\Repositories\EsbProductMaintenanceRepository;

class EsbProductMaintenanceController extends Controller
{

    use AuthorizesRequests, ValidatesRequests, StoreMessages;
    /**
     * @var FileTypeRepository
     */
    protected $file_types;
    /**
     * @var FileRepository
     */
    protected $files;
    
    
    public function __construct(RegionRepository $regions, 
                                EsbProductMaintenanceRepository $maintenances,
                                FileTypeRepository $file_types,
                                FileRepository $files)
    {
        $this->regions = $regions;
        $this->maintenances = $maintenances;
        $this->file_types = $file_types;
        $this->files = $files;
    }
    
    public function index(EsbProductMaintenanceRequest $request)
    {
        $this->maintenances->trackFilter();
        $this->maintenances->applyFilter(new ActiveFilter());
        $this->maintenances->applyFilter(new ByIdDescSortFilter());
        $this->maintenances->applyFilter(new EsbMaintenanceOwnerFilter());
        if(auth()->user()->type_id!=4) {
            $this->maintenances->pushTrackFilter(UserSearchFilter::class);
            $this->maintenances->pushTrackFilter(SearchSerialFilter::class);
            $this->maintenances->pushTrackFilter(AddressSearchFilter::class);
        }
        return view('site::esb_product_maintenance.index',[
            'repository' => $this->maintenances,
            'maintenances'    => $this->maintenances->paginate($request->input('filter.per_page', 100), ['esb_product_maintenances.*'])
        ]);
    }
    
    public function show(EsbProductMaintenance $esbProductMaintenance)
    {
        
        $this->file_types->applyFilter((new ModelHasFilesFilter())->setId($esbProductMaintenance->getAttribute('id'))->setMorph('esb_product_maintenances'));
        $file_types = $this->file_types->all();
        $files = $esbProductMaintenance->files()->get();
        
        return view('site::esb_product_maintenance.show', compact('esbProductMaintenance', 'file_types', 'files'));
    }
    
    
    public function create(esbProductMaintenanceRequest $request)
    {       
        $this->authorize('create', EsbProductMaintenance::class);
        
        if($request->esbUserProduct){
            $esbUserProduct=EsbUserProduct::findOrFail($request->esbUserProduct);
        } else {
            $esbUserProduct=null;
        }
        $service=Auth()->user();
        
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 14)->orderBy('sort_order')->get();
        $files = $this->getFiles($request);
        
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
        if(auth()->user()->hasRole(config('site.supervisor_roles'),[])){
            $engineers=User::query()->whereHas('roles', function ($q){ $q->whereIn('name',config('site.roles_engineer'));})->where('active',1)->get();

        } elseif(auth()->user()->hasRole(config('site.roles_engineer'),[])){
            $engineers=auth()->user();

        }else {
            $engineers=$service->childEngineers;
        }
        if($esbUserProduct){
            $contracts=auth()->user()->company()->esbContracts()->where('client_user_id',$esbUserProduct->user_id)->get();
        }else{
            $contracts=auth()->user()->company()->esbContracts()->get();
        }
        return view('site::esb_product_maintenance.create', compact('equipments','products', 'service','engineers','file_types','files','esbUserProduct','contracts'));
        
    }
     
    public function edit(esbProductMaintenanceRequest $request, EsbProductMaintenance $esbProductMaintenance)
    {
        if(auth()->user()->hasRole(config('site.supervisor_roles'),[])){
            $engineers=User::query()->whereHas('roles', function ($q){ $q->whereIn('name',config('site.roles_engineer'));})->where('active',1)->get();

        } elseif(auth()->user()->hasRole(config('site.roles_engineer'),[])){
            $engineers=auth()->user();

        }else {
            $engineers=auth()->user()->childEngineers;

        }

        $contracts=auth()->user()->company()->esbContracts()->where('client_user_id',$esbProductMaintenance->esbProduct->user_id)->get();

        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 14)->orderBy('sort_order')->get();
        $files = $this->getFiles($request, $esbProductMaintenance);
        return view('site::esb_product_maintenance.edit', compact('esbProductMaintenance','engineers','file_types','files','contracts'));
        
    }
    
    
    public function store(Request $request)
    {   
        $user=auth()->user();
        
        //$esbUser=esbUserProduct::find($request->input('esb_user_product_id'))->esbUser;
        
        $esbProductMaintenance=$user->esbProductMaintenances()->create($request->only(
            'number', 
            'esb_user_product_id', 
            'engineer_id' ,
            'contract_id' ,
            'date',
            'comments' 
            ));
        
        $this->setFiles($request, $esbProductMaintenance);
        
        $esbProductMaintenance->esbProduct->update(['date_sale'=>$request->input('date_sale'),'serial'=>$request->input('serial'),'service_id'=>$user->id]);
       // event(new EsbProductMaintenanceCreateEvent($repair));
        if($user->type_id!=4 && $request->input('esbClaim')) {
            $esbClaim = $esbProductMaintenance->esbProduct->esbClaim()->updateOrCreate(['esb_user_product_id'=>$esbProductMaintenance->esbProduct->id],$request->input('esbClaim'));
        }
        
        return redirect()->route('esb-product-maintenances.show',$esbProductMaintenance)->with('success', trans('site::user.esb_product_maintenance.success'));
        
    }
    
    public function update(Request $request, EsbProductMaintenance $esbProductMaintenance)
    {   
        $user=auth()->user();
        $this->setFiles($request, $esbProductMaintenance);
        $esbProductMaintenance->update($request->only(
            'number', 
            'engineer_id', 
            'contract_id' ,
            'comments'
            ));
        $esbProductMaintenance->esbProduct->update(['date_sale'=>$request->input('date_sale'),'serial'=>$request->input('serial'),'service_id'=>$user->id]);
        
        // if(Carbon::parse($request->input('date')) > Carbon::now()->subDays(32) && Carbon::parse($request->input('date')) < Carbon::now()->addDays(32)){
            $esbProductMaintenance->update(['date'=>$request->input('date')]);
        // } else {
            // return redirect()->route('esb-product-maintenances.show',$esbProductMaintenance)->with('error', trans('site::user.esb_user_product.date_works_expired'));
        // }
        
       
        return redirect()->route('esb-product-maintenances.show',$esbProductMaintenance)->with('success', trans('site::user.esb_product_maintenance.success'));
    }
    
    
    public function message(MessageRequest $request)
    {
        return $this->storeMessage($request, Auth::user());
    }
    
    public function destroy(EsbProductMaintenance $esbProductMaintenance)
	{

		//$this->authorize('delete', $order);
        $esbProductMaintenance->active=0;
        $esbProductMaintenance->save();
        
		$redirect = route('esb-product-maintenances.index');

		$json['redirect'] = $redirect;

		return response()->json($json);

	}
    
    private function getFiles(EsbProductMaintenanceRequest $request, EsbProductMaintenance $esbProductMaintenance = null)
    {
        $files = collect([]);
        $old = $request->old('file');
        if (!is_null($old) && is_array($old)) {
            foreach ($old as $type_id => $values) {
                foreach ($values as $file_id) {
                    $files->push(File::query()->findOrFail($file_id));
                }
            }
        } elseif (!is_null($esbProductMaintenance)) {
            $files = $files->merge($esbProductMaintenance->files);
        }

        return $files;
    }

    private function setFiles($request, EsbProductMaintenance $esbProductMaintenance)
    {
        $esbProductMaintenance->detachFiles();

        if ($request->filled('file')) {
            foreach ($request->input('file') as $type_id => $values) {
                foreach ($values as $file_id) {
                    $esbProductMaintenance->files()->save(File::find($file_id));
                }
            }
        }
        //$this->files->deleteLostFiles();
    }

}