<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Events\StandOrderStatusChangeEvent;
use ServiceBoiler\Prf\Site\Events\StandOrderCreateEvent;
use ServiceBoiler\Prf\Site\Events\StandOrderPaymentEvent;
use ServiceBoiler\Prf\Site\Events\StandOrderFilesEvent;
use ServiceBoiler\Prf\Site\Mail\StandOrder\AdminStandOrderConfirmEmail;
use ServiceBoiler\Prf\Site\Mail\StandOrder\AdminStandOrderStatusChangeEmail;
use ServiceBoiler\Prf\Site\Mail\StandOrder\DistrStandOrderCreateEmail;
use ServiceBoiler\Prf\Site\Mail\StandOrder\UserStandOrderStatusChangeEmail;
use ServiceBoiler\Prf\Site\Mail\StandOrder\UserStandOrderCreateEmail;
use ServiceBoiler\Prf\Site\Mail\StandOrder\AdminStandOrderCreateEmail;
use ServiceBoiler\Prf\Site\Mail\StandOrder\StandOrderPaymentEmail;
use ServiceBoiler\Prf\Site\Mail\StandOrder\StandOrderFilesEmail;

class StandOrderListener
{

	/**
	 * @param StandOrderCreateEvent $event
	 */
	public function onStandOrderCreate(StandOrderCreateEvent $event)
	{
		Mail::to([$event->standOrder->user->region->manager->email,env('MAIL_DEVEL_ADDRESS')])->send(new AdminStandOrderCreateEmail($event->standOrder));

        /*if(!empty($event->standOrder->user->region->notification_address) &  $event->standOrder->user->region->notification_address != env('MAIL_NEW_MEMBER_ADDRESS')){
		  Mail::to([$event->standOrder->user->region->notification_address , env('MAIL_STAND_ORDER_ADDRESS'),env('MAIL_SERVICE_ADDRESS')])->send(new UserStandOrderCreateEmail($event->standOrder));
		  }
        
		if ($event->standOrder->user->email) {
			Mail::to($event->standOrder->user->email)->send(new UserStandOrderCreateEmail($event->standOrder));
		}
            */
	}

	/**
	 * @param StandOrderStatusChangeEvent $event
	 */
	public function onStandOrderStatusChange(StandOrderStatusChangeEvent $event)
	{       
		
		Mail::to([env('MAIL_DEVEL_ADDRESS')])->send(new UserStandOrderStatusChangeEmail($event->standOrder));

		if ($event->standOrder->getAttribute('status_id') == 2) {
			Mail::to([$event->standOrder->warehouse_address->email,env('MAIL_DEVEL_ADDRESS')])->send(new DistrStandOrderCreateEmail($event->standOrder));
		}
		if (in_array($event->standOrder->getAttribute('status_id'),[5, 12]) ) {
			Mail::to([$event->standOrder->user->region->manager->email,env('MAIL_DEVEL_ADDRESS')])->send(new AdminStandOrderStatusChangeEmail($event->standOrder));
		}
		
        if (in_array($event->standOrder->getAttribute('status_id'),[14]) ) {
            $supervisors=User::whereHas('roles', function ($query) {
                        $query
                            ->where('name', 'supervisor_stand_orders')
                            ;
                    })->pluck('email')->toArray();
            Mail::to(array_merge($supervisors, [env('MAIL_DEVEL_ADDRESS')]))->send(new AdminStandOrderStatusChangeEmail($event->standOrder));
		}
		
        if (in_array($event->standOrder->getAttribute('status_id'),[13]) ) {
            $supervisors=User::whereHas('roles', function ($query) {
                        $query
                            ->where('name', 'admin_stand_order_worker')
                            ;
                    })->pluck('email')->toArray();
            Mail::to(array_merge($supervisors, [env('MAIL_DEVEL_ADDRESS')]))->send(new AdminStandOrderStatusChangeEmail($event->standOrder));
		}
	}

	
	public function onStandOrderPayment(StandOrderPaymentEvent $event)
	{
		
		Mail::to([$event->standOrder->user->email,env('MAIL_DEVEL_ADDRESS')])->send(new StandOrderPaymentEmail($event->standOrder));

	}

	
	public function onStandOrderFiles(StandOrderFilesEvent $event)
	{
		Mail::to(env('MAIL_DEVEL_ADDRESS'))->send(new StandOrderFilesEmail($event->standOrder));
		
	}

	
	/**
	 * @param Dispatcher $events
	 */
	public function subscribe(Dispatcher $events)
	{

		$events->listen(
			StandOrderCreateEvent::class,
			'ServiceBoiler\Prf\Site\Listeners\StandOrderListener@onStandOrderCreate'
		);

		$events->listen(
			StandOrderStatusChangeEvent::class,
			'ServiceBoiler\Prf\Site\Listeners\StandOrderListener@onStandOrderStatusChange'
		);

		$events->listen(
			StandOrderPaymentEvent::class,
			'ServiceBoiler\Prf\Site\Listeners\StandOrderListener@onStandOrderPayment'
		);

		$events->listen(
			StandOrderFilesEvent::class,
			'ServiceBoiler\Prf\Site\Listeners\StandOrderListener@onStandOrderFiles'
		);

	}
}