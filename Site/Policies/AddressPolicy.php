<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\User;

class AddressPolicy
{

    public function index(User $user)
    {
        return $user->hasPermission('addresses');
    }

    /**
     * @param User $user
     * @param Address $address
     * @return bool
     */
    public function view(User $user, Address $address)
    {
        //return $user->hasPermission('addresses') && $this->belongsUser($user, $address);
        return (in_array($address->region_id,$user->notifiRegions->pluck('id')->toArray()) && $user->hasRole('ferroli_user')) 
        || ($user->hasPermission('addresses') && $this->belongsUser($user, $address)) 
            || $user->admin == 1 
            || $user->hasPermission('admin_addresses_view')
            || $user->hasPermission('admin_addresses_update');
        
    }

    private function belongsUser(User $user, Address $address){
        return $user->id == ($address->addressable_type == 'users' ? $address->addressable->id : $address->addressable->user_id);
    }

    /**
     * @param  User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('addresses') && ($user->id > 0);
    }

    /**
     * @param User $user
     * @param Address $address
     * @return bool
     */
    public function phone(User $user, Address $address)
    {
        return $this->belongsUser($user, $address);
    }

    /**
     * @param  User $user
     * @param  Address $address
     * @return bool
     */
    public function edit(User $user, Address $address)
    {
        return ($user->hasPermission('addresses') && $this->belongsUser($user, $address))
                    || $user->hasPermission('admin_addresses_update');
    }

    /**
     * @param  User $user
     * @param  Address $address
     * @return bool
     */
    public function delete(User $user, Address $address)
    {
        return ($user->getAttribute('admin') == 1 || $user->hasPermission('addresses')) && $address->addressable->addresses()->whereTypeId($address->type_id)->count() > 1;
    }


}
