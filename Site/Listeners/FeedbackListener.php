<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\FeedbackCreateEvent;
use ServiceBoiler\Prf\Site\Mail\FeedbackEmail;

class FeedbackListener
{

    /**
     * @param FeedbackCreateEvent $event
     */
    public function onFeedbackCreate(FeedbackCreateEvent $event)
    {
        // Отправка письма о новом сообщении с сайта
        Mail::to(env('MAIL_INFO_ADDRESS'))->send(new FeedbackEmail($event->data));
        Mail::to(env('MAIL_DEVEL_ADDRESS'))->send(new FeedbackEmail($event->data));
    }


    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {

        $events->listen(
            FeedbackCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\FeedbackListener@onFeedbackCreate'
        );
    }
}
