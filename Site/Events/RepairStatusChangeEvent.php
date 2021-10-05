<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Repair;

class RepairStatusChangeEvent
{
    use SerializesModels;

    /**
     * Заказ
     *
     * @var Repair
     */
    public $repair;
    /**
     * @var string
     */
    public $adminMessage;

    /**
     * Create a new event instance.
     *
     * @param  Repair $repair
     */
    public function __construct($repair)
    {
        $this->repair = $repair;
    }
}
