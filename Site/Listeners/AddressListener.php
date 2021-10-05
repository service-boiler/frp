<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\AddressCreateEvent;
use ServiceBoiler\Prf\Site\Events\AddressUpdateEvent;
use ServiceBoiler\Prf\Site\Events\AddressApprovedChangeEvent;
use ServiceBoiler\Prf\Site\Mail\Admin\Address\AddressCreateEmail;
use ServiceBoiler\Prf\Site\Mail\Admin\Address\AddressUpdateEmail;
use ServiceBoiler\Prf\Site\Mail\User\Address\AddressApprovedChangeEmail;

class AddressListener
{

    public function onAddressCreate(AddressCreateEvent $event)
    {
        if(!empty($event->address->region->notification_address) &  $event->address->region->notification_address != env('MAIL_USER_CHANGE_ADDRESS')){
		  Mail::to([$event->address->region->notification_address , env('MAIL_USER_CHANGE_ADDRESS'),env('MAIL_SERVICE_ADDRESS')])->send(new AddressCreateEmail($event->address));
		  }
		  else {
		  Mail::to([env('MAIL_USER_CHANGE_ADDRESS'),env('MAIL_SERVICE_ADDRESS')])->send(new AddressCreateEmail($event->address));
		  }
		  
		  if(env('MAIL_DEVEL_ADDRESS')){
		  Mail::to(env('MAIL_DEVEL_ADDRESS'))->send(new AddressUpdateEmail($event->address));
		  }
    }

    public function onAddressUpdate(AddressUpdateEvent $event)
    {	
        if(!empty($event->address->region->notification_address) &  $event->address->region->notification_address != env('MAIL_USER_CHANGE_ADDRESS')){
		  Mail::to([$event->address->region->notification_address , env('MAIL_USER_CHANGE_ADDRESS'),env('MAIL_SERVICE_ADDRESS')])->send(new AddressCreateEmail($event->address));
		  }
		  else {
		  Mail::to([env('MAIL_USER_CHANGE_ADDRESS'),env('MAIL_SERVICE_ADDRESS')])->send(new AddressCreateEmail($event->address));
		  }
		  
		  if(env('MAIL_DEVEL_ADDRESS')){
		  Mail::to(env('MAIL_DEVEL_ADDRESS'))->send(new AddressUpdateEmail($event->address));
		  }
    }

    public function onAddressApproved(AddressApprovedChangeEvent $event)
    {
        
        Mail::to($event->address->user->email)->send(new AddressApprovedChangeEmail($event->address));
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            AddressCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\AddressListener@onAddressCreate'
        );

        $events->listen(
            AddressUpdateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\AddressListener@onAddressUpdate'
        );

        $events->listen(
            AddressApprovedChangeEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\AddressListener@onAddressApproved'
        );
    }
}