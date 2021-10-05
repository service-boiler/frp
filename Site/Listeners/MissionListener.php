<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Events\MissionCreateEvent;
use ServiceBoiler\Prf\Site\Events\MissionStatusChangeEvent;
use ServiceBoiler\Prf\Site\Mail\Admin\Mission\MissionCreateEmail;
use ServiceBoiler\Prf\Site\Mail\Admin\Mission\MissionExpiredEmail;
use ServiceBoiler\Prf\Site\Mail\Admin\Mission\MissionStatusChangeEmail;

class MissionListener
{

    public function onMissionCreate(MissionCreateEvent $event)
    {
        $supervisors=User::whereHas('roles', function($q){$q->where('name', 'admin_missions_subsribe');})->get();
        Mail::to($supervisors)->send(new MissionCreateEmail($event->mission));
    }

    public function onMissionExpired(MissionExpiredEvent $event)
    {   
        $supervisors=User::whereHas('roles', function($q){$q->where('name', 'admin_missions_subsribe');})->get();
        Mail::to($supervisors)->send(new MissionExpiredEmail($event->mission));
    }

    
    public function onMissionStatus(MissionStatusChangeEvent $event)
    {  
        $supervisors=User::whereHas('roles', function($q){$q->where('name', 'admin_missions_subsribe');})->get();
                     
        switch($event->mission->status->id) {
            case 33:
                
            default:
                Mail::to($supervisors)->send(new MissionStatusChangeEmail($event->mission));
                break;
        
        }
        
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            MissionCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\MissionListener@onMissionCreate'
        );

        $events->listen(
            MissionExpiredEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\MissionListener@onMissionExpired'
        );

        $events->listen(
            MissionStatusChangeEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\MissionListener@onMissionStatus'
        );
    }
}