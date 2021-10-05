<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\EsbContract;
use ServiceBoiler\Prf\Site\Models\User;

class EsbContractPolicy
{

    
    public function create(User $user)
    {
        return $user->type_id != 4;
    }

    public function view(User $user)
    {
        return $user->type_id != 4;
    }

    public function delete(User $user, EsbContract $esbContract)
    {
        //return $user->hasPermission('contracts') && $this->belongsUser($user, $esbContractTemplate);
        return 1;
    }

    public function edit(User $user, EsbContract $esbContract)
    {
        //return $user->hasPermission('contracts') && $this->belongsUser($user, $esbContractTemplate);
        return 1;
    }
}
