<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Events\AuthorizationExpiredEvent;
use ServiceBoiler\Prf\Site\Events\TenderExpiredEvent;
use ServiceBoiler\Prf\Site\Events\TenderCurrencyExpiredEvent;
use ServiceBoiler\Prf\Site\Events\TicketExpiredEvent;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\Tender;
use ServiceBoiler\Prf\Site\Models\Ticket;
use ServiceBoiler\Prf\Site\Models\Authorization;

class CronController extends Controller
{

	public function day()
	{
        if(($tenders = Tender::currencyExpired())->exists()) {
        
            $managers = $tenders->groupBy('manager_id')->pluck('manager_id');
            foreach($managers as $manager_id){
                $managerTenders = Tender::currencyExpired()->where('manager_id',$manager_id);
                $manager = User::find($manager_id);
                event(new TenderCurrencyExpiredEvent($managerTenders, $manager));
            
            }
        }
        
        if(($tenders = Tender::expired())->exists()) {
            $managers = $tenders->groupBy('manager_id')->pluck('manager_id');
            foreach($managers as $manager_id){
                $managerTenders = Tender::expired()->where('manager_id',$manager_id);
                $manager = User::find($manager_id);
               event(new TenderExpiredEvent($managerTenders, $manager));
            
            }
                       
        }
        
        
        if(($authorizations = Authorization::expired())->exists()) {
        
            foreach($authorizations->get() as $authorization) {
                event(new AuthorizationExpiredEvent($authorization));
            }
            
        }
          
        if(($tickets = Ticket::expired())->exists()) {
            foreach($tickets->get() as $ticket) {
                event(new TicketExpiredEvent($ticket));
            }
            
        }elseif(($tickets = Ticket::waiting())->exists()) {
            foreach($tickets->get() as $ticket) {
                event(new TicketExpiredEvent($ticket));
            }
            
        }
        

	}

}
