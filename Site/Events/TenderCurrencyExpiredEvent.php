<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Tender;
use ServiceBoiler\Prf\Site\Models\User;

class TenderCurrencyExpiredEvent
{
    use SerializesModels;

    
    public $tenders;
    public $manager;
    /**
     * @var string
     */
    public $adminMessage;

     public function __construct($tenders , $manager)
    {
        $this->tenders = $tenders;
        $this->manager = $manager;
    }
}
