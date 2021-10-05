<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\User;

class EsbProductMaintenancePolicy
{

    
public function create(User $user)
    {
        return $user->type_id != 4;
    }
}
