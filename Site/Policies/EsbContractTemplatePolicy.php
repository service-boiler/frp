<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\EsbContractTemplate;
use ServiceBoiler\Prf\Site\Models\User;

class EsbContractTemplatePolicy
{

    
    public function create(User $user)
    {
        return $user->type_id != 4;
    }

    public function view(User $user)
    {
        return $user->type_id != 4;
    }

    public function delete(User $user, EsbContractTemplate $esbContractTemplate)
    {
        //return $user->hasPermission('contracts') && $this->belongsUser($user, $esbContractTemplate);
        return !$esbContractTemplate->esbContracts()->exists();
    }
}
