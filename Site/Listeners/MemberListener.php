<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\MemberCreateEvent;
use ServiceBoiler\Prf\Site\Mail\Admin\Member\MemberCreateEmail;
use ServiceBoiler\Prf\Site\Mail\MemberConfirmationEmail;

class MemberListener
{

    /**
     * Обработчик события:
     * Создание заявки на мероприятие
     *
     * @param MemberCreateEvent $event
     */
    public function onMemberCreate(MemberCreateEvent $event)
    {
        
        // Отправка автору заявки на мероприятие письма о подтверждении E-mail
        Mail::to($event->member->getAttribute('email'))->send(new MemberConfirmationEmail($event->member));

        if(!empty($event->member->region->notification_address) &  $event->member->region->notification_address != env('MAIL_NEW_MEMBER_ADDRESS')){
		  Mail::to([$event->member->region->notification_address , env('MAIL_NEW_MEMBER_ADDRESS'),env('MAIL_SERVICE_ADDRESS')])->send(new MemberCreateEmail($event->member));
		  }
		  else {
		  Mail::to([env('MAIL_NEW_MEMBER_ADDRESS'),env('MAIL_SERVICE_ADDRESS')])->send(new MemberCreateEmail($event->member));
		  }
		  
		  if(env('MAIL_DEVEL_ADDRESS')){
		  Mail::to(env('MAIL_DEVEL_ADDRESS'))->send(new MemberCreateEmail($event->member));
		  }
    }


    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            MemberCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\MemberListener@onMemberCreate'
        );

    }
}