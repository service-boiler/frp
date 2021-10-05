<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\AuthorizationCreateEvent;
use ServiceBoiler\Prf\Site\Events\AuthorizationStatusChangeEvent;
use ServiceBoiler\Prf\Site\Events\AuthorizationPreAcceptedEvent;
use ServiceBoiler\Prf\Site\Events\AuthorizationExpiredEvent;
use ServiceBoiler\Prf\Site\Mail\Admin\Authorization\AuthorizationCreateEmail;
use ServiceBoiler\Prf\Site\Mail\Admin\Authorization\AuthorizationStatusChangeEmail;
use ServiceBoiler\Prf\Site\Mail\Admin\Authorization\AuthorizationPreAcceptedEmail;
use ServiceBoiler\Prf\Site\Mail\Admin\Authorization\AuthorizationExpiredEmail;
use ServiceBoiler\Prf\Site\Models\User;

class AuthorizationListener
{

    /**
     * @param AuthorizationCreateEvent $event
     * Отправляем письмо на service, старшему менеджеру и менеджеру региона
     */
    public function onAuthorizationCreate(AuthorizationCreateEvent $event)
    {  
          
        if(!empty($event->authorization->user->region->notification_address) 
                &&  $event->authorization->user->region->notification_address != env('MAIL_AUTHORIZATION_ADDRESS')){
            $region_manager = [$event->authorization->user->region->notification_address];
        } else {
            $region_manager = [env('MAIL_AUTHORIZATION_ADDRESS')];
        }
        
            Mail::to($region_manager)->send(new AuthorizationCreateEmail($event->authorization));
		 
		  
    }
    
    public function onAuthorizationStatus(AuthorizationStatusChangeEvent $event)
    {  
        
        if(!empty($event->authorization->user->region->notification_address) 
                &&  $event->authorization->user->region->notification_address != env('MAIL_AUTHORIZATION_ADDRESS')){
            $region_manager = [$event->authorization->user->region->notification_address];
        } else {
            $region_manager = [env('MAIL_AUTHORIZATION_ADDRESS')];
        }
        
        
        Mail::to($region_manager)->send(new AuthorizationStatusChangeEmail($event->authorization));
		  
		  
    }
    
    public function onAuthorizationPreAccepted(AuthorizationPreAcceptedEvent $event)
    {  
        $supervisors=User::whereHas('roles', function ($query) {
                        $query
                            ->where('name', 'service_super')
                            ;
                    })->pluck('email')->toArray();
        if(in_array($event->authorization->role->authorization_role->id,[1])){          
          Mail::to($supervisors)->
            send(new AuthorizationPreAcceptedEmail($event->authorization));
        }
		  
    }
    
      public function onAuthorizationExpired(AuthorizationExpiredEvent $event)
    {  
        
        $supervisors=User::whereHas('roles', function ($query) {$query->where('name', 'service_super'); })->pluck('email')->toArray();
        
        if(!empty($event->authorization->user->region->notification_address) 
                &&  $event->authorization->user->region->notification_address != env('MAIL_AUTHORIZATION_ADDRESS')){
            $region_manager = [$event->authorization->user->region->notification_address];
        } else {
            $region_manager = [env('MAIL_AUTHORIZATION_ADDRESS')];
        }
        
        if(in_array($event->authorization->role->authorization_role->id,[1])){
            $emails = array_merge($region_manager, $supervisors);
            
        } else {
            $emails = $region_manager;
        }
        
        
            Mail::to(array_merge($emails))->send(new AuthorizationExpiredEmail($event->authorization));
		
    }


    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            AuthorizationStatusChangeEvent::class, 'ServiceBoiler\Prf\Site\Listeners\AuthorizationListener@onAuthorizationStatus'
        );

        $events->listen(
            AuthorizationCreateEvent::class, 'ServiceBoiler\Prf\Site\Listeners\AuthorizationListener@onAuthorizationCreate'
        );

        $events->listen(
            AuthorizationPreAcceptedEvent::class, 'ServiceBoiler\Prf\Site\Listeners\AuthorizationListener@onAuthorizationPreAccepted'
        );
        $events->listen(
            AuthorizationExpiredEvent::class, 'ServiceBoiler\Prf\Site\Listeners\AuthorizationListener@onAuthorizationExpired'
        );

    }
}