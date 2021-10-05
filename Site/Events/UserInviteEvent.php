<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\User;

class UserInviteEvent
{
    use SerializesModels;

 
    public $user;

	 public function __construct($user)
    {
        $this->user = $user;
		  
    }
}
