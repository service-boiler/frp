<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Events\TicketCreateEvent;
use ServiceBoiler\Prf\Site\Events\TicketExpiredEvent;
use ServiceBoiler\Prf\Site\Events\TicketStatusChangeEvent;
use ServiceBoiler\Prf\Site\Mail\Admin\Ticket\TicketCreateEmail;
use ServiceBoiler\Prf\Site\Mail\Admin\Ticket\TicketExpiredEmail;
use ServiceBoiler\Prf\Site\Mail\Admin\Ticket\TicketStatusChangeEmail;
use ServiceBoiler\Prf\Site\Pdf\TicketResultPdf;

class TicketListener
{

    public function onTicketCreate(TicketCreateEvent $event)
    {
        
        Mail::to([env('MAIL_DEVEL_ADDRESS'),$event->ticket->receiver->email])->send(new TicketCreateEmail($event->ticket));
    }

    public function onTicketExpired(TicketExpiredEvent $event)
    {
        Mail::to([$event->ticket->receiver->email,env('MAIL_SERVICE_ADDRESS'),env('MAIL_DEVEL_ADDRESS')])->send(new TicketExpiredEmail($event->ticket));
    }

    
    public function onTicketStatus(TicketStatusChangeEvent $event)
    {  
                     
        switch($event->ticket->status->id) {
            case 3:
                Mail::to([env('MAIL_DEVEL_ADDRESS'),$event->ticket->receiver->email])->send(new TicketStatusChangeEmail($event->ticket));
                break;
            case 4:
                Mail::to([env('MAIL_DEVEL_ADDRESS'),$event->ticket->receiver->email])->send(new TicketStatusChangeEmail($event->ticket));
                break;
            case 5:
                Mail::to([env('MAIL_DEVEL_ADDRESS'),$event->ticket->receiver->email])->send(new TicketStatusChangeEmail($event->ticket));
                break;
           
            default:
                Mail::to([env('MAIL_DEVEL_ADDRESS'),$event->ticket->receiver->email])->send(new TicketStatusChangeEmail($event->ticket));
                break;
        
        }
        
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            TicketCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\TicketListener@onTicketCreate'
        );

        $events->listen(
            TicketExpiredEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\TicketListener@onTicketExpired'
        );

        $events->listen(
            TicketStatusChangeEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\TicketListener@onTicketStatus'
        );
    }
}