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
use ServiceBoiler\Prf\Site\Filters\EnabledFilter;
use ServiceBoiler\Prf\Site\Filters\ByIdDescSortFilter;
use ServiceBoiler\Prf\Site\Filters\EsbRepair\EsbRepairAddressSearchFilter;
use ServiceBoiler\Prf\Site\Filters\EsbRepair\EsbRepairSearchSerialFilter;
use ServiceBoiler\Prf\Site\Filters\EsbRepair\EsbRepairUserSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SelectFilter;
use ServiceBoiler\Prf\Site\Http\Requests\ImageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\ServiceIdRequest;
use ServiceBoiler\Prf\Site\Http\Requests\EsbRepairRequest;
use ServiceBoiler\Prf\Site\Models\EsbUserProduct;
use ServiceBoiler\Prf\Site\Models\EsbRepair;
use ServiceBoiler\Prf\Site\Models\Image;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\EsbPart;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Repositories\EsbRepairRepository;

class EsbRepairController extends Controller
{

    use AuthorizesRequests, ValidatesRequests, StoreMessages;
    
    
    
    public function __construct(EsbRepairRepository $esbRepairs)
                                
    {
        $this->esbRepairs = $esbRepairs;
        
    }
    
    public function index(EsbRepairRequest $request)
    {
        $this->esbRepairs->trackFilter();
        $this->esbRepairs->applyFilter(new EnabledFilter());
        $this->esbRepairs->applyFilter(new ByIdDescSortFilter());
		$this->esbRepairs->pushTrackFilter(EsbRepairUserSearchFilter::class);
		$this->esbRepairs->pushTrackFilter(EsbRepairSearchSerialFilter::class);
		$this->esbRepairs->pushTrackFilter(EsbRepairAddressSearchFilter::class);
        
       
        return view('site::esb_repair.index',[
            'repository' => $this->esbRepairs,
            'esbRepairs'    => $this->esbRepairs->paginate($request->input('filter.per_page', 100), ['esb_repairs.*'])
        ]);
    }
    
    public function show(EsbRepair $esbRepair)
    {
        
        
        return view('site::esb_repair.show', compact('esbRepair'));
    }
    
    
    public function create(esbRepairRequest $request)
    {       
        $userProducts=collect();
        if($request->input('esb_repair.client_id')){
            $userProducts=User::find($request->input('esb_repair.client_id'))->esbProducts()->whereEnabled(1)->get();
        }
        
        $parts = $this->getParts($request);
        
        if($request->esbUserProduct){
            $esbUserProduct=EsbUserProduct::findOrFail($request->esbUserProduct);
            $userProducts=$esbUserProduct->esbUser->esbProducts()->whereEnabled(1)->get();
        } else {
            $esbUserProduct=null;
        }
        
        $service=Auth()->user();
       
        $engineers=$service->childEngineers;
        $clients=$service->esbUsers()->orderBy('name')->get();
        
        return view('site::esb_repair.create', compact('service','engineers','esbUserProduct','clients','userProducts','parts'));
        
    }
     
    public function edit(esbRepairRequest $request, EsbRepair $esbRepair)
    {   
       $userProducts=collect();
        if($request->input('esb_repair.client_id')){
            $userProducts=User::find($request->input('esb_repair.client_id'))->esbProducts()->whereEnabled(1)->get();
        }elseif($esbRepair->client_id){
            $userProducts=$esbRepair->client->esbProducts()->whereEnabled(1)->get();
        }
        
        $parts = $this->getParts($request, $esbRepair);
        
        if($request->esbUserProduct){
            $esbUserProduct=EsbUserProduct::findOrFail($request->esbUserProduct);
        } else {
            $esbUserProduct=null;
        }
        $service=Auth()->user();
        $repair_price_ratio=$service->repair_price_ratio;
       $engineers=auth()->user()->childEngineers;
       $clients=$service->esbUsers()->orderBy('name')->get();
       return view('site::esb_repair.edit', compact('esbRepair','service','engineers','esbUserProduct','clients','userProducts','parts','repair_price_ratio'));
        
    }
    
    
    public function store(Request $request)
    {   
    
        $user=auth()->user();
        //dd($request->input('esb_repair'));
        //$esbUser=esbUserProduct::find($request->input('esb_user_product_id'))->esbUser;
        
        $esbRepair=$user->esbRepairs()->create($request->input('esb_repair'));
        $esbProduct=$esbRepair->esbProduct;
        $esbProduct->service_id=$user->company()->id;
        $esbProduct->save();
        
       if ($request->filled('count')) {
            $parts = (collect($request->input('count')))->map(function ($data, $product_id) {
                $product = Product::query()->findOrFail($product_id);
                return new EsbPart([
                    'product_id' => $product_id,
                    'count'=> $data['count'],
                    'cost' => $data['cost'],
                    'product_name' => $product->name,
                ]);
            });
            $esbRepair->parts()->saveMany($parts);
        } 
       
        return redirect()->route('esb-repairs.show',$esbRepair)->with('success', trans('site::user.esb_repair.created'));
        
    }
    
    public function update(Request $request, EsbRepair $esbRepair)
    {   
        
        $esbRepair->update($request->input('esb_repair'));
        
        $esbRepair->parts()->delete();
        if ($request->filled('count')) {
            $parts = (collect($request->input('count')))->map(function ($data, $product_id) {
                $product = Product::query()->findOrFail($product_id);
                return new EsbPart([
                    'product_id' => $product_id,
                    'count'=> $data['count'],
                    'cost' => $data['cost'],
                    'product_name' => $product->name,
                ]);
            });
            
            $esbRepair->parts()->saveMany($parts);
        } 
       
        return redirect()->route('esb-repairs.show',$esbRepair)->with('success', trans('site::user.esb_repair.updated'));
    }
    
    
    public function message(MessageRequest $request)
    {
        return $this->storeMessage($request, Auth::user());
    }
    
    public function destroy(EsbRepair $esbRepair)
	{

		//$this->authorize('delete', $order);
        $esbRepair->enabled=0;
        $esbRepair->save();
        
		$redirect = route('esb-repairs.index');

		$json['redirect'] = $redirect;

		return response()->json($json);

	}
    
   
    
    private function getParts(EsbRepairRequest $request, EsbRepair $esbRepair = null)
    {   
        $parts = collect([]);
        $old = $request->old('count');
        
        if (!is_null($old) && is_array($old)) {

            foreach ($old as $product_id => $data) {
                $product = Product::query()->findOrFail($product_id);
                $parts->put($product->id, collect([
                    'product' => $product,
                    'count'   => $data['count'],
                    'cost' => $data['cost'],
                ]));
            }
        } elseif (!is_null($esbRepair)) {
        
            foreach ($esbRepair->parts as $data) {
                $parts->put($data->product_id, collect([
                    'product' => $data->product,
                    'count'      => $data->count,
                    'cost' => $data->cost,
                ]));
            }
        }
        return $parts;
    }

}