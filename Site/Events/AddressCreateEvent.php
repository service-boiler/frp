<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Address;

class AddressCreateEvent
{
    use SerializesModels;

    /**
     * Заказ
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $address;

    /**
     * Create a new event instance.
     *
     * @param  Address $address
     */
    public function __construct($address)
    {
        $this->address = $address;
    }
}
