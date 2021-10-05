<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Address;

class AddressUpdateEvent
{
    use SerializesModels;

 
    public $address;

	 public function __construct($address)
    {
        $this->address = $address;
		  
    }
}
