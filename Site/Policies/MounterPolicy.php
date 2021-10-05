<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Mounter;
use ServiceBoiler\Prf\Site\Models\User;

class MounterPolicy
{


    /**
     * @param User $user
     * @param Mounter $mounter
     * @return bool
     */
    public function view(User $user, Mounter $mounter)
    {
        return $user->id == $mounter->userAddress->addressable->id && $mounter->userAddress->addressable_type == 'users';
    }

    /**
     * @param User $user
     * @param Mounter $mounter
     * @return bool
     */
    public function edit(User $user, Mounter $mounter)
    {
        return $user->id == $mounter->userAddress->addressable->id && $mounter->userAddress->addressable_type == 'users';
    }

}
