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
use ServiceBoiler\Prf\Site\Events\EsbProductLaunchCreateEvent;
use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\EsbProductLaunch\EsbProductLaunchOwnerFilter;
use ServiceBoiler\Prf\Site\Filters\ActiveFilter;
use ServiceBoiler\Prf\Site\Filters\FileType\ModelHasFilesFilter;
use ServiceBoiler\Prf\Site\Filters\FileType\EsbProductLaunchFilter;
use ServiceBoiler\Prf\Site\Filters\FileType\SortFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SelectFilter;
use ServiceBoiler\Prf\Site\Http\Requests\ImageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\ServiceIdRequest;
use ServiceBoiler\Prf\Site\Http\Requests\EsbProductLaunchRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\Equipment;
use ServiceBoiler\Prf\Site\Models\EsbClaim;
use ServiceBoiler\Prf\Site\Models\EsbClaimQuestion;
use ServiceBoiler\Prf\Site\Models\EsbClaimQuestionAnswer;
use ServiceBoiler\Prf\Site\Models\EsbClaimQuestionValue;
use ServiceBoiler\Prf\Site\Models\EsbUserProduct;
use ServiceBoiler\Prf\Site\Models\EsbProductLaunch;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Models\Image;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\FileRepository;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;
use ServiceBoiler\Prf\Site\Repositories\EsbProductLaunchRepository;

class EsbProductLaunchController extends Controller
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
                                EsbProductLaunchRepository $launches,
                                FileTypeRepository $file_types,
                                FileRepository $files)
    {
        $this->regions = $regions;
        $this->launches = $launches;
        $this->file_types = $file_types;
        $this->files = $files;
    }
    
    public function index(EsbProductLaunchRequest $request)
    {      
        
        $this->launches->trackFilter();
        $this->launches->applyFilter(new ActiveFilter());
        $this->launches->applyFilter(new EsbProductLaunchOwnerFilter());
       
        return view('site::esb_product_launch.index',[
            'repository' => $this->launches,
            'launches'    => $this->launches->paginate($request->input('filter.per_page', 100), ['esb_product_launches.*'])
        ]);
    }
    
    public function show(EsbProductLaunch $esbProductLaunch)
    {
        
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 13)->orderBy('sort_order')->get();
        $files = $esbProductLaunch->files()->get();
        $answersHeat = $esbProductLaunch->esbProduct->esbClaim ? $esbProductLaunch->esbProduct->esbClaim->answers()->whereHas('question', function ($q) {$q->where('theme_id',1);})->get() : null;
        $answersElectric = $esbProductLaunch->esbProduct->esbClaim ? $esbProductLaunch->esbProduct->esbClaim->answers()->whereHas('question', function ($q) {$q->where('theme_id',2);})->get() : null;
        $answersSmoke = $esbProductLaunch->esbProduct->esbClaim ? $esbProductLaunch->esbProduct->esbClaim->answers()->whereHas('question', function ($q) {$q->where('theme_id',3);})->get() : null;

        return view('site::esb_product_launch.show', compact('esbProductLaunch', 'file_types', 'files','answersHeat','answersElectric','answersSmoke'));
    }
    
    
    public function create(esbProductLaunchRequest $request)
    {     
        if($request->esbUserProduct){
            $esbUserProduct=EsbUserProduct::findOrFail($request->esbUserProduct);
        } else {
            $esbUserProduct=null;
        }
        $service=Auth()->user();
        
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 13)->orderBy('sort_order')->get();
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
        $engineers=$service->childEngineers;
        $questions=EsbClaimQuestion::where('enabled',1)->get();

        return view('site::esb_product_launch.create', compact('equipments','products', 'service','engineers','file_types','files','esbUserProduct','questions'));
        
    }
     
    public function edit(esbProductLaunchRequest $request, EsbProductLaunch $esbProductLaunch)
    {
        $answers = array();
        if($esbProductLaunch->esbProduct->esbClaim) {
            foreach ($esbProductLaunch->esbProduct->esbClaim->answers()->get() as $answer) {
                $answers[$answer->question_id] = ['value_id' => $answer->value_id, 'value_text' => $answer->value_text];
            }
        }
        $questions=EsbClaimQuestion::where('enabled',1)->get();
        $engineers=auth()->user()->childEngineers;
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 13)->orderBy('sort_order')->get();
        $files = $this->getFiles($request, $esbProductLaunch);
        return view('site::esb_product_launch.edit', compact('esbProductLaunch','engineers','file_types','files','questions','answers'));
        
    }
    
    
    public function store(Request $request)
    {   

        $user=auth()->user();
        $esbUser=esbUserProduct::find($request->input('esb_user_product_id'))->esbUser;
        
        $launch=$user->esbProductLaunches()->create(array_merge(['esb_user_id'=>$esbUser->id],$request->only(
            'number', 
            'esb_user_product_id', 
            'engineer_id' ,
            'contract_id' ,
            'date_launch' ,
            'launcher_text' ,
            'comments' 
            )));
        if(empty($request->input('launcher_text'))){
            $launch->launcher_text=$user->company()->public_name;
            $launch->save();
        }
        $launch->esbProduct->update(['date_sale'=>$request->input('date_sale'),'serial'=>$request->input('serial')]);
        
        $this->setFiles($request, $launch);
        
        if($user->type_id!=4) {
            $launch->approved=1;
            $launch->save();
            
            $launch->esbProduct->update(['date_sale'=>$request->input('date_sale'),'serial'=>$request->input('serial'),'service_id'=>$user->id]);
            $esbClaim = $launch->esbProduct->esbClaim()->updateOrCreate(['esb_user_product_id'=>$launch->esbProduct->id],$request->input('esb_claim'));

            if(!empty($request->input('esb_claim.answers'))){
                foreach ($request->input('esb_claim.answers') as $question_id=>$answer){
                    if(EsbClaimQuestion::find($question_id) && EsbClaimQuestion::find($question_id)->type_id==2 &&  EsbClaimQuestionValue::find($answer)){
                        $esbClaim->answers()->updateOrCreate(['question_id'=>$question_id],['value_id'=>$answer]);
                    } else {
                        $esbClaim->answers()->updateOrCreate(['question_id'=>$question_id],['value_text'=>$answer]);
                    }
                }


            }
        } else {
            $launch->created_by_consumer=1;
            if($request->service_id) {
                $launch->service_id=$request->service_id;


            } else {
                $launch->service_id=null;
            }
            $launch->save();
            
        }
        event(new EsbProductLaunchCreateEvent($launch));


        return redirect()->route('esb-product-launches.show',$launch)->with('success', trans('site::user.esb_product_launch.success'));
        
    }
    
    public function update(Request $request, EsbProductLaunch $esbProductLaunch)
    {   
        $user=auth()->user();
        
        if($request->input('updsrv')){
            $esbProductLaunch->esbProduct->service_id=$request->input('service_id');
            $esbProductLaunch->esbProduct->save();
            event(new EsbProductLaunchCreateEvent($esbProductLaunch));
           $receiver_id = $request->input('service_id');
                $text="Новые данные о вводе в экплуатацию от клиента.";
                $esbProductLaunch->messages()->save($request->user()->outbox()->create(['text'=>$text, 'receiver_id'=>$receiver_id, 'personal'=>'0']));
            return redirect()->back()->with('success', trans('site::user.esb_request.success_user_launch'));
        }
        
        $esbProductLaunch->update($request->only(
            'number', 
            'engineer_id', 
            'contract_id' ,
            'launcher_text' ,
            'comments'
            ));
        $esbProductLaunch->esbProduct->update(['date_sale'=>$request->input('date_sale'),'serial'=>$request->input('serial'),'service_id'=>$user->id]);
        $this->setFiles($request, $esbProductLaunch);
        if($user->type_id!=4) {
            $esbProductLaunch->approved=$request->approved;

            $esbProductLaunch->save();
            $esbClaim = $esbProductLaunch->esbProduct->esbClaim()->updateOrCreate(['esb_user_product_id'=>$esbProductLaunch->esbProduct->id],$request->input('esb_claim'));

            if(!empty($request->input('esb_claim.answers'))){
                foreach ($request->input('esb_claim.answers') as $question_id=>$answer){
                    if(EsbClaimQuestion::find($question_id) && EsbClaimQuestion::find($question_id)->type_id==2 &&  EsbClaimQuestionValue::find($answer)){
                        $esbClaim->answers()->updateOrCreate(['question_id'=>$question_id],['value_id'=>$answer]);
                    } else {
                        $esbClaim->answers()->updateOrCreate(['question_id'=>$question_id],['value_text'=>$answer]);
                    }
                }


            }
        }
        
        // if(Carbon::parse($request->input('date_launch')) > Carbon::now()->subDays(32) && Carbon::parse($request->input('date_launch')) < Carbon::now()->addDays(32)){
            $esbProductLaunch->update(['date_launch'=>$request->input('date_launch')]);
        // } else {
            // return redirect()->route('esb-product-launches.show',$esbProductLaunch)->with('error', trans('site::user.esb_user_product.date_works_expired'));
        // }
        
        return redirect()->route('esb-product-launches.show',$esbProductLaunch)->with('success', trans('site::user.esb_product_launch.success'));
    }
    
    
    public function message(MessageRequest $request)
    {
        return $this->storeMessage($request, Auth::user());
    }
    
    public function destroy(EsbProductLaunch $esbProductLaunch)
	{

		//$this->authorize('delete', $order);
        $esbProductLaunch->active=0;
        $esbProductLaunch->save();
        
		$redirect = route('esb-product-launches.index');

		$json['redirect'] = $redirect;

		return response()->json($json);

	}
    
    private function getFiles(EsbProductLaunchRequest $request, EsbProductLaunch $esbProductLaunch = null)
    {
        $files = collect([]);
        $old = $request->old('file');
        if (!is_null($old) && is_array($old)) {
            foreach ($old as $type_id => $values) {
                foreach ($values as $file_id) {
                    $files->push(File::query()->findOrFail($file_id));
                }
            }
        } elseif (!is_null($esbProductLaunch)) {
            $files = $files->merge($esbProductLaunch->files);
        }

        return $files;
    }

    private function setFiles($request, EsbProductLaunch $esbProductLaunch)
    {
        $esbProductLaunch->detachFiles();

        if ($request->filled('file')) {
            foreach ($request->input('file') as $type_id => $values) {
                foreach ($values as $file_id) {
                    $esbProductLaunch->files()->save(File::find($file_id));
                }
            }
        }
        //$this->files->deleteLostFiles();
    }

}