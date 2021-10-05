<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\User;

class EsbServiceDeleteEvent
{
    use SerializesModels;

   
    public $esbUser;
    public $service;

   
    public function __construct(User $esbUser, User $service)
    {
        $this->esbUser = $esbUser;
        $this->service = $service;
    }
}
