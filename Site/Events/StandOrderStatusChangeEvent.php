<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\StandOrder;

class StandOrderStatusChangeEvent
{
    use SerializesModels;

    /**
     * Заявка на авторизацию
     *
     * @var StandOrder
     */
    public $standOrder;

    /**
     * Create a new event instance.
     *
     * @param  StandOrder $order
     */
    public function __construct(StandOrder $standOrder)
    {
        $this->standOrder = $standOrder;
    }
}
