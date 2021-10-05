<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Mission;

class MissionStatusChangeEvent
{
    use SerializesModels;

    /**
     * Заказ
     *
     * @var Mission
     */
    public $mission;
    /**
     * @var string
     */
    public $adminMessage;

    /**
     * Create a new event instance.
     *
     * @param  Mission $mission
     */
    public function __construct($mission)
    {
        $this->mission = $mission;
    }
}
