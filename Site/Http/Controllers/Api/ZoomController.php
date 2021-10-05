<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Exceptions\Zoom\ZoomException;
use ServiceBoiler\Prf\Site\Facades\Site;
use ServiceBoiler\Prf\Site\Models\Webinar;
use ServiceBoiler\Prf\Site\Models\WebinarUnauthParticipant;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Services\Zoom;

class ZoomController extends Controller
{
    

    /**
     * @return ProductCollection
     */
    public function createZoomWebinar(Webinar $webinar)
    {   
        $response = (new Zoom())->createWebinar([
                                                "topic" => 'Test '.$webinar->name,
                                                "agenda" => $webinar->annotation,
                                                "start_time" => $webinar->datetime->format('Y-m-d\TH:i:s'),
                                                "duration" => $webinar->duration_planned
                                                ])->request();

			if (in_array($response->getStatusCode(),['200','201'])) {
				$body = json_decode($response->getBody(), true);
				if (!empty($body['id'])) {
					
                    $webinar->update(['zoom_id'=>$body['id']]);
                    
				} else {
					throw new ZoomException($body['message']);
				}
			}
            
        return view('site::zoom.callback', compact('product'));
    }
    
    public function getWebinar($webinar_id)
    {   
       // dd($webinar_id);
        $response = (new Zoom())->getWebinar($webinar_id)->request();
        
        dd(json_decode($response->getBody()));
			if ($response->getStatusCode() === Response::HTTP_OK) {
				$body = json_decode($response->getBody(), true);
				if ($body['code'] == 0) {
					$this->digiftUser()->create([
						'id' => $digiftUserData['id'],
						'accessToken' => $body['result']['accessToken'],
						'tokenExpired' => $body['result']['tokenExpired'],
						'fullUrlToRedirect' => $body['result']['fullUrlToRedirect'],
					]);
				} else {
					throw new ZoomException($body['message']);
				}
			}
    
        return view('site::zoom.callback', compact('product'));
    }
    public function addWebinarRegistrant($webinar_id)
    {   
       
        $response = (new Zoom())->addWebinarRegistrant($webinar_id)->request();
        
        dd(json_decode($response->getBody()));
        
    
        return view('site::zoom.callback', compact('product'));
    }
    
    
    public function addWebinarUserRegistrant(Webinar $webinar, User $user)
    {   
       
        $response = (new Zoom())->addWebinarUserRegistrant($webinar, $user)->request();
        
        dd(json_decode($response->getBody()));
        return view('site::zoom.callback', compact('product'));
    }
    
    
    public function getWebinarRegistrant(Webinar $webinar, $registrant_id)
    {   
       //dd($webinar);
        $response = (new Zoom())->getWebinarRegistrant($webinar, $registrant_id)->request();
        
        dd(json_decode($response->getBody()));
        
    
        return view('site::zoom.callback', compact('product'));
    }
    public function getWebinarRegistrantQuestions(Webinar $webinar)
    {   
       //dd($webinar);
        $response = (new Zoom())->getWebinarRegistrantQuestions($webinar)->request();
        
        dd(json_decode($response->getBody()));
        
    
        return view('site::zoom.callback', compact('product'));
    }
    
    public function getWebinarsStatistic()
    {   //dd(Webinar::whereNotNull('zoom_id')->get());
        foreach(Webinar::whereNotNull('zoom_id')->get() as $webinar) {
           // $webinar=Webinar::find(23);
            
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
            } //--- getStatusCode
        }
        return 1;
    }
    
   
   public function callback()
    {
        $response = (new Zoom())->getWebinarStatistic('93805311134')->request();
        dd(json_decode($response->getBody()));
			if ($response->getStatusCode() === Response::HTTP_OK) {
				$body = json_decode($response->getBody(), true);
				if ($body['code'] == 0) {
					$this->digiftUser()->create([
						'id' => $digiftUserData['id'],
						'accessToken' => $body['result']['accessToken'],
						'tokenExpired' => $body['result']['tokenExpired'],
						'fullUrlToRedirect' => $body['result']['fullUrlToRedirect'],
					]);
				} else {
					throw new ZoomException($body['message']);
				}
			}
    
        return view('site::zoom.callback', compact('product'));
    }

    
}