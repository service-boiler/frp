<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Events\TenderExpiredEvent;
use ServiceBoiler\Prf\Site\Events\TenderCurrencyExpiredEvent;
use ServiceBoiler\Prf\Site\Mail\Admin\Tender\TenderExpiredEmail;
use ServiceBoiler\Prf\Site\Mail\Admin\Tender\TenderCurrencyExpiredEmail;

class TenderExpiredListener
{

    
    public function onTenderExpired(TenderExpiredEvent $event)
    {   
               Mail::to(array_merge([$event->manager->email,$event->manager->chief->email],[env('MAIL_DEVEL_ADDRESS')]))
            ->send(new TenderExpiredEmail($event->tenders->get()));
        
    }
    
    public function onCurrencyTenderExpired(TenderCurrencyExpiredEvent $event)
    {  
        Mail::to(array_merge([$event->manager->email,$event->manager->chief->email],[env('MAIL_DEVEL_ADDRESS')]))
            ->send(new TenderCurrencyExpiredEmail($event->tenders->get()));
             
        
    }
        
    

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            TenderExpiredEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\TenderExpiredListener@onTenderExpired'
        );
        
        $events->listen(
            TenderCurrencyExpiredEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\TenderExpiredListener@onCurrencyTenderExpired'
        );
    }
}