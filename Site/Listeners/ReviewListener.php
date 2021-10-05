<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\ReviewCreateEvent;
use ServiceBoiler\Prf\Site\Mail\Review\AdminReviewCreateEmail;
use ServiceBoiler\Prf\Site\Mail\Review\UserReviewCreateEmail;

class ReviewListener
{

    /**
     * @param ReviewCreateEvent $event
     */
    public function onReviewCreate(ReviewCreateEvent $event)
    {

        // Отправка admin_siteистратору уведомления о новой заявке на монтаж
        Mail::to(env('MAIL_TO_ADDRESS'))->send(new AdminReviewCreateEmail($event->review));

        if ($event->review->email) {

            Mail::to($event->review->email)->send(new UserReviewCreateEmail($event->review));
        }

    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {

        $events->listen(
            ReviewCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\ReviewListener@onReviewCreate'
        );


    }
}