<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Mission;

class MissionCreateEvent
{
    use SerializesModels;

    /**
     *
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $mission;

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
