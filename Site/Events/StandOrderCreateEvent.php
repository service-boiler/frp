<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\StandOrder;

class StandOrderCreateEvent
{
    use SerializesModels;

    /**
     * Заказ
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $standOrder;

   
    public function __construct($standOrder)
    {
        $this->standOrder = $standOrder;
    }
}
