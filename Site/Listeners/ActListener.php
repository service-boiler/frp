<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\ActMountingCreateEvent;
use ServiceBoiler\Prf\Site\Events\ActRepairCreateEvent;
use ServiceBoiler\Prf\Site\Events\ActExport;
use ServiceBoiler\Prf\Site\Mail\User\Act\ActRepairCreateEmail;
use ServiceBoiler\Prf\Site\Mail\User\Act\AdminActMountingCreateEmail;

class ActListener
{


    /**
     * @param ActExport $event
     */
    public function onActSchedule(ActExport $event)
    {
        $event->act->schedules()->create([
            'action_id' => 3,
            'url'       => preg_split("/:\/\//", route('api.acts.show', $event->act))[1]
        ]);
        //Schedule::create();
    }

    /**
     * @param ActRepairCreateEvent $event
     */
    public function onActRepairCreate(ActRepairCreateEvent $event)
    {
        // Отправка пользователю письма о том, что созданы АВР
        Mail::to($event->user->email)->send(new ActRepairCreateEmail($event->user, $event->acts));
    }

    /**
     * @param ActRepairCreateEvent $event
     */
    public function onActMountingCreate(ActMountingCreateEvent $event)
    {
        // Отправка admin_siteистратору уведомления о создании нового АВР по монтажам
        Mail::to($event->user->email)->send(new AdminActMountingCreateEmail($event->user, $event->acts));
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {

        $events->listen(
            ActExport::class,
            'ServiceBoiler\Prf\Site\Listeners\ActListener@onActSchedule'
        );

        $events->listen(
            ActRepairCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\ActListener@onActRepairCreate'
        );

        $events->listen(
            ActMountingCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\ActListener@onActMountingCreate'
        );
    }
}