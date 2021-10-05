<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\MounterCreateEvent;
use ServiceBoiler\Prf\Site\Mail\Mounter\AdminMounterCreateEmail;
use ServiceBoiler\Prf\Site\Mail\Mounter\UserMounterCreateEmail;

class MounterListener
{

    /**
     * @param MounterCreateEvent $event
     */
    public function onMounterCreate(MounterCreateEvent $event)
    {

        // Отправка admin_siteистратору уведомления о новой заявке на монтаж
        Mail::to(env('MAIL_TO_ADDRESS'))->send(new AdminMounterCreateEmail($event->mounter));

        if ($event->mounter->userAddress->email) {

            // Отправка СЦ уведомления о новой заявке на монтаж
            Mail::to($event->mounter->userAddress->email)->send(new UserMounterCreateEmail($event->mounter));
        }

    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {

        $events->listen(
            MounterCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\MounterListener@onMounterCreate'
        );


    }
}