<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Address;

class AddressApprovedChangeEvent
{
    use SerializesModels;
    public $address;
    public function __construct(Address $address)
    {
        $this->address = $address;
    }
}
