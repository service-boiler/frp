<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\Digift\BonusCreateEvent;
use ServiceBoiler\Prf\Site\Events\MountingCreateEvent;
use ServiceBoiler\Prf\Site\Events\MountingStatusChangeEvent;
use ServiceBoiler\Prf\Site\Mail\Admin\Mounting\MountingCreateEmail;
use ServiceBoiler\Prf\Site\Mail\User\Mounting\BonusCreateEmail;
use ServiceBoiler\Prf\Site\Mail\User\Mounting\MountingStatusChangeEmail;

class MountingListener
{

    /**
     * @param MountingCreateEvent $event
     */
    public function onMountingCreate(MountingCreateEvent $event)
    {
        Mail::to(env('MAIL_TO_ADDRESS'))->send(new MountingCreateEmail($event->mounting));
        Mail::to(env('MAIL_MOUNTING_COPY_ADDRESS'))->send(new MountingCreateEmail($event->mounting));
    }

    /**
     * @param MountingStatusChangeEvent $event
     */
    public function onMountingStatusChange(MountingStatusChangeEvent $event)
    {
        Mail::to($event->mounting->user->email)->send(new MountingStatusChangeEmail($event->mounting));
    }


	/**
	 * @param BonusCreateEvent $event
	 */
	public function onBonusCreate(BonusCreateEvent $event)
	{
		Mail::to(env('MAIL_BONUS_ADDRESS'))->send(new BonusCreateEmail($event->mounting));
		Mail::to(env('MAIL_MOUNTING_COPY_ADDRESS'))->send(new BonusCreateEmail($event->mounting));
	}


    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {

        $events->listen(
            MountingCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\MountingListener@onMountingCreate'
        );

        $events->listen(
            MountingStatusChangeEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\MountingListener@onMountingStatusChange'
        );

        $events->listen(
	        BonusCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\MountingListener@onBonusCreate'
        );

    }
}