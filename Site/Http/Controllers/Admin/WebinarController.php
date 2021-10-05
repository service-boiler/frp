<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Exceptions\Zoom\ZoomException;
use ServiceBoiler\Prf\Site\Concerns\StoreImages;
use ServiceBoiler\Prf\Site\Filters\Webinar\WebinarPromocodeFilter;
use ServiceBoiler\Prf\Site\Filters\Webinar\WebinarThemeFilter;
use ServiceBoiler\Prf\Site\Filters\Webinar\WebinarTypeFilter;
use ServiceBoiler\Prf\Site\Models\Promocode;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\Webinar;
use ServiceBoiler\Prf\Site\Models\WebinarTheme;
use ServiceBoiler\Prf\Site\Models\WebinarType;
use ServiceBoiler\Prf\Site\Models\WebinarUnauthParticipant;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\WebinarRequest;
use ServiceBoiler\Prf\Site\Repositories\WebinarThemeRepository;
use ServiceBoiler\Prf\Site\Repositories\WebinarRepository;
use ServiceBoiler\Prf\Site\Services\Zoom;

class WebinarController extends Controller
{
    use StoreImages;
    
    
    protected $webinars;

    /**
     * Create a new controller instance.
     *
     * @param WebinarRepository $webinars
     */
    public function __construct(
        WebinarRepository $webinars, 
        WebinarThemeRepository $webinarThemes)
    {
        $this->webinars = $webinars;
        $this->WebinarThemeRepository = $webinarThemes;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->webinars->trackFilter();
        $this->webinars->pushTrackFilter(WebinarTypeFilter::class);
        $this->webinars->pushTrackFilter(WebinarThemeFilter::class);
        $this->webinars->pushTrackFilter(WebinarPromocodeFilter::class);
        
        return view('site::admin.webinar.index', [
            'repository' => $this->webinars,
            'webinars'  => $this->webinars->paginate(config('site.per_page.webinar', 100), ['webinars.*'])
        ]);
    }

    public function show(Webinar $webinar)
    {   
       return view('site::admin.webinar.show', compact('webinar'));
    }
    
    public function createZoomWebinar(Webinar $webinar)
    {   
       $response = (new Zoom())->createWebinar([
                                                "topic" => $webinar->name,
                                                "agenda" => $webinar->annotation,
                                                "start_time" => $webinar->datetime->format('Y-m-d\TH:i:s'),
                                                "duration" => $webinar->duration_planned
                                                ])->request();

			if (in_array($response->getStatusCode(),['200','201'])) {
				$body = json_decode($response->getBody(), true);
				if (!empty($body['id'])) {
					
                    $webinar->update([
                    'zoom_id'=>$body['id'],
                    'link_service'=>$body['registration_url']
                    ]);
                    
                    
				} else {
					throw new ZoomException($body['message']);
				}
			}
       $response = (new Zoom())->addWebinarRegistrantDefaultQuestions($webinar)->request();
       
       return $redirect = redirect()->route('ferroli-user.webinars.show', $webinar)->with('success', trans('site::admin.webinar.zoom_webinar_created'));
    }
    
    public function getWebinarStatistic(Webinar $webinar)
    {       
            $response = (new Zoom())->getWebinarStatistic($webinar)->request();
            if(in_array($response->getStatusCode(),[201,200])) {
            
                $body = json_decode($response->getBody(), true);
                $participants = $body['participants'];
              
                foreach($participants as $participant) {
                    $user=User::where('email',$participant['user_email'])->first();
                    if(!empty($user)){
                        
                        if (empty($webinar->participants()->find($user->id))) {
                            
                            $webinar->AttachParticipant($user); 
                            $join_time=Carbon::parse($participant['join_time'])->setTimezone('Europe/Moscow');
                            $leave_time=Carbon::parse($participant['leave_time'])->setTimezone('Europe/Moscow');
                        } else {
                            $join_time_stored = $webinar->participants()->find($user->id)->pivot->join_time ? Carbon::parse($webinar->participants()->find($user->id)->pivot->join_time) : null;
                            $leave_time_stored = $webinar->participants()->find($user->id)->pivot->leave_time ? Carbon::parse($webinar->participants()->find($user->id)->pivot->leave_time) : null;
                            $join_time_new = Carbon::parse($participant['join_time'])->setTimezone('Europe/Moscow');
                            $leave_time_new = Carbon::parse($participant['leave_time'])->setTimezone('Europe/Moscow');
                            
                            if( $join_time_stored!= null && $join_time_stored < $join_time_new){
                                $join_time=$join_time_stored;
                            } else {
                                $join_time=$join_time_new;
                            }
                            if( $leave_time_stored!= null && $leave_time_stored > $leave_time_new){
                                $leave_time=$leave_time_stored;
                            } else {
                                $leave_time=$leave_time_new;
                            }
                        }
                        $duration=$join_time->diffInMinutes($leave_time);
                        
                        $webinar->participants()->updateExistingPivot($user, [
                                                                            'zoom_registrant_id' => $participant['id'],
                                                                            'duration' => $duration,
                                                                            'join_time' => $join_time,
                                                                            'leave_time' => $leave_time,
                                                                            'visit' => 1,
                                                                            'checkin' => 1,
                                                                            'comment' => $participant['name'] ." " .$participant['user_id'] ." " .$participant['join_time']
                                                                            
                                                                            ]); 
                                                                            
                        
                    } else {
                        
                        $response = (new Zoom())->getWebinarRegistrant($webinar,$participant['id'])->request();
                        $body = json_decode($response->getBody(), true);
                        
                        $address = !empty($body['address']) ? $body['address'] : null;
                        $city = !empty($body['city']) ? $body['city'] : null;
                        $country = !empty($body['country']) ? $body['country'] : null;
                        $state = !empty($body['state']) ? $body['state'] : null;
                        $phone = !empty($body['phone']) ? $body['phone'] : null;
                        $org = !empty($body['org']) ? $body['org'] : null;
                        
                        $uparticipant=['webinar_id'=>$webinar->id, 
                              'checkin' => '1', 
                              'visit' => '1', 
                              'name'=>$participant['name'], 
                              'phone'=>$phone, 
                              'company'=>$org, 
                              'duration'=>round($participant['duration']/60,0), 
                              'region'=>$state, 
                              'locality'=>$city, 
                              'email'=>$participant['user_email']
                                  ];
                        
                        if(($unapt = WebinarUnauthParticipant::where('email',$participant['user_email'])->where('webinar_id',$webinar->id))->exists()) {
                           $unapt->update($uparticipant);
                        }else {
                            $unapt = WebinarUnauthParticipant::query()->create($uparticipant);
                        }
                    
                    }
                    
                    
                }
                return $redirect = redirect()->route('ferroli-user.webinars.show', $webinar)->with('success', trans('site::admin.webinar.zoom_webinar_stat_updated'));
            }else{
                return $redirect = redirect()->route('ferroli-user.webinars.show', $webinar)->with('success', trans('site::admin.webinar.zoom_webinar_stat_empty'));
            }
        
        
    }
    
    public function addWebinarUserRegistrant(Webinar $webinar, User $user)
    {   
       $response = (new Zoom())->addWebinarUserRegistrant($webinar, $user)->request();

			if (in_array($response->getStatusCode(),['200','201'])) {
				$body = json_decode($response->getBody(), true);
				if (!empty($body['registrant_id'])) {
					
                    $webinar->AttachParticipant($user,'asdasd');
                    dd($webinar->participants()->updateExistingPivot($user, ['zoom_registrant_id' => 'asdasdasd']));
                    
				} else {
					throw new ZoomException($body['message']);
				}
			}
       
       return $redirect = redirect()->route('ferroli-user.webinars.show', $webinar)->with('success', trans('site::admin.webinar.zoom_webinar_created'));
    }
    
   public function create(WebinarRequest $request)
    {   
        $image = $this->getImage($request);
        $promocodes = Promocode::query()->orderBy('name')->get();   
        $themes = WebinarTheme::query()->orderBy('name')->get();   
        $types = WebinarType::query()->orderBy('name')->get();   
        return view('site::admin.webinar.create',compact('themes','types','promocodes'));
    }
   
   
   public function store(WebinarRequest $request)
    {
        
        $webinar = $this->webinars->create(array_merge(
                            $request->input('webinar'),['enabled' => $request->filled('webinar.enabled')]));

        return $redirect = redirect()->route('ferroli-user.webinars.index')->with('success', trans('site::admin.webinar.created'));
    }
    
  /**
     * @param Webinar $webinar
     * @return \Illuminate\Http\Response
     */
    public function edit(WebinarRequest $request, Webinar $webinar)
    {   
       
        $promocodes = Promocode::query()->orderBy('name')->get();   
        $themes = WebinarTheme::query()->orderBy('name')->get();   
        $types = WebinarType::query()->orderBy('name')->get();    
        $image = $this->getImage($request, $webinar);
        return view('site::admin.webinar.edit', compact('webinar','themes','types','promocodes','image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  WebinarRequest $request
     * @param  Webinar $webinar
     * @return \Illuminate\Http\Response
     */
    public function update(WebinarRequest $request, Webinar $webinar)
    {
        $webinar->update(array_merge(
                            $request->input('webinar'),['enabled' => $request->filled('webinar.enabled')]));
       
        $redirect = redirect()->route('ferroli-user.webinars.show', $webinar)->with('success', trans('site::admin.webinar.updated'));
        
        
        return $redirect;
    }
    
      public function destroy(webinar $webinar)
    {

        if ($webinar->delete()) {
            return redirect()->route('ferroli-user.webinars.index')->with('success', trans('site::admin.webinar.deleted'));
        } else {
            return redirect()->route('ferroli-user.webinars.show', $webinar)->with('error', trans('site::admin.webinar.error_deleted'));
        }
    }

 
}