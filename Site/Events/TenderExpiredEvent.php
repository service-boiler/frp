<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Tender;
use ServiceBoiler\Prf\Site\Models\User;

class TenderExpiredEvent
{
    use SerializesModels;

    /**
     * Заказ
     *
     * @var Tender
     */
    public $tender;
    /**
     * @var string
     */
    public $adminMessage;
    public $tenders;
    public $manager;

    /**
     * Create a new event instance.
     *
     * @param  Tender $tender
     */
    public function __construct($tenders , $manager)
    {
        $this->tenders = $tenders;
        $this->manager = $manager;
    }
}
