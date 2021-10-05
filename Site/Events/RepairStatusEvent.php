<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Repair;

class RepairStatusEvent
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
     * @param null $adminMessage
     */
    public function __construct($repair, $adminMessage = null)
    {
        $this->repair = $repair;
        $this->adminMessage = $adminMessage;
    }
}
