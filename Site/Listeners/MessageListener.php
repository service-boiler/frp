<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\MessageCreateEvent;
use ServiceBoiler\Prf\Site\Mail\MessageCreateEmail;

class MessageListener
{

    /**
     * @param MessageCreateEvent $event
     */
    public function onMessageCreate(MessageCreateEvent $event)
    {
     switch(class_basename($event->message->messagable)) {
        case 'StandOrder': 
            $ADMIN_ADDRESS=env('MAIL_STAND_ORDER_ADDRESS');
            break;
        default:
            $ADMIN_ADDRESS=env('MAIL_TO_ADDRESS');
            break;
        
        }
        
        
        if($event->message->receiver->id == 1){
                Mail::to($ADMIN_ADDRESS)->send(new MessageCreateEmail($event->message));
            if(class_basename($event->message->messagable)=='Tender') {
                $tender=$event->message->messagable;
                Mail::to($tender->chief->email)->send(new MessageCreateEmail($event->message));
            } else {
                 Mail::to($ADMIN_ADDRESS)->send(new MessageCreateEmail($event->message));
            }
             
		}
		else {
        // Отправка получателю уведомления о новом сообщени
	    Mail::to($event->message->receiver->email)->send(new MessageCreateEmail($event->message));}
	    

    }


    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {

        $events->listen(
            MessageCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\MessageListener@onMessageCreate'
        );

    }
}
