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
use ServiceBoiler\Prf\Site\Events\EsbRequestCreateEvent;

use ServiceBoiler\Prf\Site\Events\EsbVisitStatusEvent;
use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\ByIdDescSortFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SelectFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SortFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserVisit\DateEsbUserVisitFromFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserVisit\DateEsbUserVisitToFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserVisit\EsbUserVisitIdSearchFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserVisit\EsbUserVisitUserEsbFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserVisit\EsbUserVisitServiceEsbFilter;
use ServiceBoiler\Prf\Site\Filters\EsbUserVisit\EsbUserVisitUserSearchFilter;

use ServiceBoiler\Prf\Site\Http\Requests\ImageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\ProfileEsbRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\EsbRequestType;
use ServiceBoiler\Prf\Site\Models\EsbUserVisit;
use ServiceBoiler\Prf\Site\Models\EsbUserVisitType;
use ServiceBoiler\Prf\Site\Models\AuthorizationRole;
use ServiceBoiler\Prf\Site\Models\Image;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;
use ServiceBoiler\Prf\Site\Repositories\EsbUserVisitRepository;

class EsbUserVisitController extends Controller
{

    use AuthorizesRequests, ValidatesRequests, StoreMessages;
    
    public function __construct(RegionRepository $regions, EsbUserVisitRepository $esbUserVisits)
    {
        $this->regions = $regions;
        $this->esbUserVisits = $esbUserVisits;
    }
    
    public function index(Request $request)
    {
        $user=auth()->user();
        $this->esbUserVisits->trackFilter();
        $this->esbUserVisits->applyFilter(new ByIdDescSortFilter());
        if($user->type_id==4) {
            $this->esbUserVisits->applyFilter(new EsbUserVisitUserEsbFilter());
        } else {
            $this->esbUserVisits->applyFilter(new EsbUserVisitServiceEsbFilter());
            $this->esbUserVisits->pushTrackFilter(EsbUserVisitUserSearchFilter::class);
        }
            
        $this->esbUserVisits->pushTrackFilter(DateEsbUserVisitFromFilter::class);
        $this->esbUserVisits->pushTrackFilter(DateEsbUserVisitToFilter::class);
        $this->esbUserVisits->pushTrackFilter(EsbUserVisitIdSearchFilter::class);
        $esbUserVisits = $this->esbUserVisits->paginate($request->input('filter.per_page', 100), ['esb_user_visits.*']);
        $repository = $this->esbUserVisits;
        $engineers=$user->childEngineers;
        $types=EsbUserVisitType::where('enabled',1)->orderBy('sort_order')->get();
        

        return view('site::esb_user_visit.index', compact('esbUserVisits', 'repository','user','engineers','types'));
    }
    
    
    public function create(Request $request)
    {   
        $visit_types=EsbUserVisitType::where('enabled',1)->orderBy('sort_order')->get();
        $user=Auth()->user();
        $client=User::find($request->client_id);
        $services=User::whereHas('roles', function ($q){$q->whereName('asc');})->get();
        $engineers=User::whereHas('roles', function ($q){$q->whereIn('name',config('site.roles_engineer'));})->get();

        return view('site::esb_user_visit.create', compact('services','client','user','visit_types','engineers'));
    }
    
    public function store(EsbUserVisitRequest $request)
    {   
        $multiple=$request->multiple;
        $user=Auth()->user();
        
        if(!$request->input('recipient')){
            return redirect()->route('home')->with('error','Заявака не отправлена, не выбран сервисный центр');
        }
        
        foreach($request->input('recipient', []) as $recipient_id)
		{
            if(User::query()->find($recipient_id)) {
                $esbRequest = $user->esbRequests()->create(array_merge($request->only('type_id','date_planned','phone','contact_name','user_product_id','comments'),
                                                                ['recipient_id'=>$recipient_id]));
            }
        }
		
        event(new EsbRequestCreateEvent($esbRequest));
		
		return redirect()->route('home')->with('success','Заявка успешно отправлена. В ближайшее время с Вами свяжется представитель сервисного центра');
    }
    
    
	public function status(Request $request, EsbUserVisit $esbUserVisit)
	{
   
            if(in_array($request->input('esbUserVisit.status_id'),$esbUserVisit->statuses()->pluck('id')->toArray()))
            {
                if($request->input('esbUserVisit.status_id')==6) {
                    $esbUserVisit->status_id=3;
                } else {
                    $esbUserVisit->status_id=$request->input('esbUserVisit.status_id');
                }
                $esbUserVisit->save();
                          
            }
            if($request->input('changedate')){
                $esbUserVisit->date_planned=$request->input('datetime');
                if($request->user()->type_id==4) {
                    $esbUserVisit->status_id=5;
                } else {
                    $esbUserVisit->status_id=2; 
                }
                $esbUserVisit->save();
            }
            if($request->input('changeengineer')){
                $esbUserVisit->engineer_id=$request->input('engineer_id');
                $esbUserVisit->save();
            }
            if($request->input('changetype')){
                $esbUserVisit->type_id=$request->input('type_id');
                $esbUserVisit->save();
            }
            if($request->input('changecost')){
                $esbUserVisit->cost_planned=$request->input('cost_planned');
                $esbUserVisit->save();
            }
            if($request->input('comments')){
                $esbUserVisit->comments=$request->input('comments');
                $esbUserVisit->save();
            }
        event(new EsbVisitStatusEvent($esbUserVisit));
		return redirect()->back()->with('success', trans('site::user.esb_user_visit.updated'));

	}
    
    public function message(MessageRequest $request)
    {
        return $this->storeMessage($request, Auth::user());
    }
}