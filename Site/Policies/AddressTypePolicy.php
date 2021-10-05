<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\AddressType;
use ServiceBoiler\Prf\Site\Models\User;

class AddressTypePolicy
{

    public function create(User $user, AddressType $address_type)
    {

        $address_types = AddressType::where('enabled', 1)->pluck('id');
        if ($user->hasRole(config('site.warehouse_check', []), false)) {
            $address_types->push(6);
        }

        return $user->hasPermission('addresses') && ($user->id > 0) && $address_types->contains($address_type->id);
    }


}
