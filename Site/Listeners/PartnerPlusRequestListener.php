<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Events\PartnerPlusRequestStatusChangeEvent;
use ServiceBoiler\Prf\Site\Events\PartnerPlusRequestCreateEvent;
use ServiceBoiler\Prf\Site\Mail\PartnerPlusRequest\AdminPartnerPlusRequestStatusChangeEmail;
use ServiceBoiler\Prf\Site\Mail\PartnerPlusRequest\DistrPartnerPlusRequestCreateEmail;
use ServiceBoiler\Prf\Site\Mail\PartnerPlusRequest\AdminPartnerPlusRequestCreateEmail;

class PartnerPlusRequestListener
{

	/**
	 * @param PartnerPlusRequestCreateEvent $event
	 */
	public function onPartnerPlusRequestCreate(PartnerPlusRequestCreateEvent $event)
	{
		
          $supervisors=User::whereHas('roles', function ($query) {
                        $query
                            ->where('name', 'admin_pp_subscribe');
                    })->pluck('email')->toArray();
        
         Mail::to(array_merge([env('MAIL_DEVEL_ADDRESS')],$supervisors))->send(new AdminPartnerPlusRequestCreateEmail($event->partnerPlusRequest));            
		
	}

	/**
	 * @param PartnerPlusRequestStatusChangeEvent $event
	 */
	public function onPartnerPlusRequestStatusChange(PartnerPlusRequestStatusChangeEvent $event)
	{       
		
        
          $supervisors=User::whereHas('roles', function ($query) {
                        $query
                            ->where('name', 'admin_pp_subscribe');
                    })->pluck('email')->toArray();
        
        Mail::to(array_merge([env('MAIL_DEVEL_ADDRESS')],$supervisors))->send(new AdminPartnerPlusRequestStatusChangeEmail($event->partnerPlusRequest));            
		
        if ($event->partnerPlusRequest->getAttribute('status_id') == 3) {
			Mail::to($event->partnerPlusRequest->distributor->email)->send(new AdminPartnerPlusRequestStatusChangeEmail($event->partnerPlusRequest));
		}
		
	}


	
	/**
	 * @param Dispatcher $events
	 */
	public function subscribe(Dispatcher $events)
	{

		$events->listen(
			PartnerPlusRequestCreateEvent::class,
			'ServiceBoiler\Prf\Site\Listeners\PartnerPlusRequestListener@onPartnerPlusRequestCreate'
		);

		$events->listen(
			PartnerPlusRequestStatusChangeEvent::class,
			'ServiceBoiler\Prf\Site\Listeners\PartnerPlusRequestListener@onPartnerPlusRequestStatusChange'
		);

		

	}
}