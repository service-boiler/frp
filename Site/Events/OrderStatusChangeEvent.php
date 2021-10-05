<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Order;

class OrderStatusChangeEvent
{
    use SerializesModels;

    /**
     * Заявка на авторизацию
     *
     * @var Order
     */
    public $order;

    /**
     * Create a new event instance.
     *
     * @param  Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
