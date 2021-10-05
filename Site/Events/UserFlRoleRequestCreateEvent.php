<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\UserFlRoleRequest;

class UserFlRoleRequestCreateEvent
{
    use SerializesModels;

    public $userFlRoleRequest;

    public function __construct(UserFlRoleRequest $userFlRoleRequest)
    {
        $this->userFlRoleRequest = $userFlRoleRequest;
    }
}
