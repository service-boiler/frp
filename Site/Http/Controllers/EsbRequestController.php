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
use ServiceBoiler\Prf\Site\Events\EsbRequestCreateEvent;

use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\ByIdDescSortFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SelectFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SortFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserRequest\EsbUserRequestDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserRequest\EsbUserRequestDateToFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserRequest\EsbUserRequestIdSearchFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserRequest\EsbUserRequestUserEsbFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserRequest\EsbUserRequestServiceEsbFilter;

use ServiceBoiler\Prf\Site\Http\Requests\ImageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\ProfileEsbRequest;
use ServiceBoiler\Prf\Site\Http\Requests\EsbUserRequestRequest;

use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\AuthorizationRole;
use ServiceBoiler\Prf\Site\Models\EsbRequestType;
use ServiceBoiler\Prf\Site\Models\EsbUserRequest;
use ServiceBoiler\Prf\Site\Models\EsbUserProduct;
use ServiceBoiler\Prf\Site\Models\EsbUserVisit;
use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Models\Image;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;
use ServiceBoiler\Prf\Site\Repositories\EsbUserRequestRepository;

class EsbRequestController extends Controller
{

    use AuthorizesRequests, ValidatesRequests, StoreMessages;
    
    public function __construct(RegionRepository $regions, EsbUserRequestRepository $esbUserRequests)
    {
        $this->regions = $regions;
        $this->esbUserRequests = $esbUserRequests;
    }
    
    public function index(Request $request)
    {
        $user=auth()->user();
        $this->esbUserRequests->trackFilter();
        
        
        $this->esbUserRequests->applyFilter(new ByIdDescSortFilter());
        if($user->type_id==4) {
            $this->esbUserRequests->applyFilter(new EsbUserRequestUserEsbFilter());
        } else {
            $this->esbUserRequests->applyFilter(new EsbUserRequestServiceEsbFilter());
        }
        $this->esbUserRequests->pushTrackFilter(EsbUserRequestDateFromFilter::class);    
        $this->esbUserRequests->pushTrackFilter(EsbUserRequestDateToFilter::class);    
        $this->esbUserRequests->pushTrackFilter(EsbUserRequestIdSearchFilter::class);    
         
        $esbUserRequests = $this->esbUserRequests->paginate($request->input('filter.per_page', 100), ['esb_user_requests.*']);
        $repository = $this->esbUserRequests;
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 16)->orderBy('sort_order')->get();
        

        return view('site::esb_request.index', compact('esbUserRequests', 'repository','user','file_types'));
    }
    
    
    public function create(EsbUserRequestRequest $request)
    {   

        $multiple=$request->multiple;
        $request_types=EsbRequestType::where('enabled',1)->orderBy('sort_order')->get();
        $user=Auth()->user();
        $services=User::whereHas('roles', function ($q){$q->whereName('asc');})
                    ->whereHas('addresses', function ($q) use ($user){$q->where('region_id',$user->region_id);})->get();
        $checkedService=$user->esbServices()->wherePivot('enabled',1)->first();
        
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 16)->orderBy('sort_order')->get();
        $files = $this->getFiles($request);
       
        if($request->esbUserProduct){
            $esbUserProduct=EsbUserProduct::findOrFail($request->esbUserProduct);
        } else {
            $esbUserProduct=null;
        }

        return view('site::esb_request.create', compact('services','checkedService','multiple','user','request_types','file_types','files','esbUserProduct'));
    }
    
    public function store(EsbUserRequestRequest $request)
    {   
        $multiple=$request->multiple;
        $user=Auth()->user();
        
        if(!$request->input('recipient')){
            return redirect()->route('home')->with('error','Заявка не отправлена, не выбран сервисный центр');
        }
        
        foreach($request->input('recipient', []) as $recipient_id)
		{
            if(User::query()->find($recipient_id)) {
                $esbRequest = $user->esbRequests()->create(array_merge($request->only('date_planned','phone','contact_name','user_product_id','comments'),
                                                                ['recipient_id'=>$recipient_id,'type_id'=>$request->request_type_id]));
                $this->setFiles($request, $esbRequest);
                $receiver_id = $recipient_id;
                $text="Новая заявка от клиента.";
                $esbRequest->messages()->save($request->user()->outbox()->create(['text'=>$text, 'receiver_id'=>$receiver_id, 'personal'=>'0']));
            }
        }
		 
            
        event(new EsbRequestCreateEvent($esbRequest));
		
		return redirect()->route('home')->with('success','Заявка успешно отправлена. В ближайшее время с Вами свяжется представитель сервисного центра');
    }
    
    
	public function status(EsbUserRequestRequest $request, EsbUserRequest $esbUserRequest)
	{
   
            if(in_array($request->input('esbUserRequest.status_id'),$esbUserRequest->statuses()->pluck('id')->toArray()))
            {
                $esbUserRequest->status_id=$request->input('esbUserRequest.status_id');
                $esbUserRequest->save();
                
                if($request->input('esbUserRequest.status_id')== 2) {
               
                    $visit=EsbUserVisit::create([
                                     'esb_user_product_id'=>$esbUserRequest->user_product_id,
                                     'client_user_id'=>$esbUserRequest->esb_user_id,
                                     'type_id'=>$esbUserRequest->type_id,
                                     'service_user_id'=>$esbUserRequest->recipient_id,
                                     'date_planned'=>$esbUserRequest->date_planned ? $esbUserRequest->date_planned->format('d.m.Y') . ' 12:00' : null]);
        
                    $visit->status_id=1;
                    $visit->save();
                    
                    $esbUserRequest->esb_user_visit_id=$visit->id;
                    $esbUserRequest->save();
                    return redirect()->route('esb-visits.index',['filter[search_id]'=>$visit->id])->with('success', trans('site::user.esb_user_visit.created_from_request'));
                    
                }
                
            }
            
		
		return redirect()->route('esb-requests.index')->with('success', trans('site::user.esb_request.updated'));

	}
    
    public function message(MessageRequest $request)
    {
        return $this->storeMessage($request, Auth::user());
    }
    
  private function getFiles(EsbUserRequestRequest $request, EsbUserRequest $esbUserRequest = null)
    {
        $files = collect([]);
        $old = $request->old('file');
        if (!is_null($old) && is_array($old)) {
            foreach ($old as $type_id => $values) {
                foreach ($values as $file_id) {
                    $files->push(File::query()->findOrFail($file_id));
                }
            }
        } elseif (!is_null($esbUserRequest)) {
            $files = $files->merge($esbUserRequest->files);
        }

        return $files;
    }

    private function setFiles($request, EsbUserRequest $esbUserRequest)
    {
        $esbUserRequest->detachFiles();

        if ($request->filled('file')) {
            foreach ($request->input('file') as $type_id => $values) {
                foreach ($values as $file_id) {
                    $esbUserRequest->files()->save(File::find($file_id));
                }
            }
        }
        //$this->files->deleteLostFiles();
    }

}