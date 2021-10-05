<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\Webinar\WebinarIndexDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Webinar\SortAscFilter;
use ServiceBoiler\Prf\Site\Models\Webinar;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\WebinarUnauthParticipant;
use ServiceBoiler\Prf\Site\Repositories\HeadBannerBlockRepository;
use ServiceBoiler\Prf\Site\Repositories\VariableRepository;
use ServiceBoiler\Prf\Site\Repositories\WebinarRepository;
use ServiceBoiler\Prf\Site\Http\Requests\ParticipantRequest;
use ServiceBoiler\Prf\Site\Services\Zoom;


class WebinarController extends Controller
{
    protected $webinars;

    public function __construct(
        WebinarRepository $webinars,
        VariableRepository $variables,
        HeadBannerBlockRepository $headBannerBlocks)
    {
        $this->webinars = $webinars;
        $this->variables = $variables;
        $this->headBannerBlocks = $headBannerBlocks;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->webinars->trackFilter();
        $this->webinars->applyFilter( new WebinarIndexDateFromFilter());
        $this->webinars->applyFilter( new SortAscFilter());
        

        return view('site::webinar.index', [
            'repository' => $this->webinars,
            'webinars'  => $this->webinars->paginate(config('site.per_page.webinar', 100), ['webinars.*'])
        ]);
    }
    
    public function publicWebinars()
    {
        $this->webinars->trackFilter();
        
        $this->webinars->applyFilter( new WebinarIndexDateFromFilter());
        $this->webinars->applyFilter( new SortAscFilter());
        
        $headBannerBlocks = $this->headBannerBlocks
            ->applyFilter(new Filters\ShowFilter())
            ->applyFilter(new Filters\BySortOrderFilter())
            ->applyFilter(new Filters\PathUrlFilter())
            ->all(['head_banner_blocks.*']);
			
		
        $variables = $this->variables
            ->applyFilter(new Filters\Variable\AcademyFilter())
            ->all(['variables.*']);
        
        $vars = $variables->pluck('value','name');
        
        
    	
        return view('site::event.webinar_index', [
            'repository' => $this->webinars,
            'webinars'     => $this->webinars->paginate(50, ['webinars.*']),
            'vars'     => $vars,
            'headBannerBlocks' => $headBannerBlocks
        ]);
    }

    public function showPublicWebinar(Webinar $webinar)
    {   
       return view('site::webinar.show_public', compact('webinar'));
    }
    
    public function joinPublicWebinarForm(Webinar $webinar)
    {   
       return view('site::webinar.join_public', compact('webinar'));
    }
    
    public function joinPublicWebinar(Webinar $webinar, ParticipantRequest $request)
    {   
        $participant = WebinarUnauthParticipant::query()->create(array_merge($request->input('participant'),['webinar_id'=>$webinar->id, 'checkin' => '1']));
       
       if(empty($webinar->id_service)) {
        return $redirect = redirect()->route('events.webinars.show', $webinar)->with('error', trans('site::admin.webinar.no_id_service'));
        }
        if($webinar->service_name=='zoom'){
        return  redirect($webinar->link_service);
        } else {
       return $redirect = redirect()->route('events.webinars.public_enter', [$webinar, $participant]);
       }
    }
    
    public function show(Webinar $webinar)
    {   
       return view('site::webinar.show', compact('webinar'));
    }
    
    public function enter(Webinar $webinar)
    {   
        $user = Auth::user();
        
        if($user->type_id==3){ 
            if($webinar->registerUserOnWebinar($user)=='you_denied') {
                return $redirect = redirect()->route('webinars.show', $webinar)->with('error', trans('site::admin.webinar.you_denied'));
            } else {
                if($webinar->service_name=='zoom' && !empty($webinar->participants()->find($user->id)->pivot->zoom_reigistrant_join_url)){
                    return  redirect($webinar->participants()->find($user->id)->pivot->zoom_reigistrant_join_url);
                } else {
                    return  redirect($webinar->link_service);
                }
            }
        } else {
            return  redirect($webinar->link_service);
        }
        
      
    }
   
   public function enterPublicWebinar(Webinar $webinar, WebinarUnauthParticipant $participant)
    {   return  redirect($webinar->link_service);
       //return view('site::webinar.enter_public', compact('webinar','participant'));
    }

    
    public function register(Webinar $webinar)
    {
        $user = Auth::user();
        
        if($webinar->registerUserOnWebinar($user)=='you_denied') {
            return $redirect = redirect()->route('webinars.show', $webinar)->with('error', trans('site::admin.webinar.you_denied'));
        } else {
            return $redirect = redirect()->route('webinars.show', $webinar)->with('success', trans('site::admin.webinar.you_registered'));
        }
        
    } 
    
    public function unregister(Webinar $webinar)
    {
        $user = Auth::user();
        

       if ($webinar->participants->contains($user->id)) {
                    $webinar->detachParticipant($user);
                    return $redirect = redirect()->route('webinars.show', $webinar)->with('success', trans('site::admin.webinar.you_unregistered'));
                }
        else {
            return $redirect = redirect()->route('webinars.show', $webinar)->with('success', trans('site::admin.webinar.you_unregistered_earlier'));
        }
       
        
        
    }
    
    public function view(Webinar $webinar)
    {   $user = Auth::user();
       return view('site::webinar.view', compact('webinar','user'));
    }
    
    public function viewPublicWebinar(Webinar $webinar)
    {  
       return view('site::webinar.view_public', compact('webinar'));
    }

 
}