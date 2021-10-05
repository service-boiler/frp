<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Order;
use ServiceBoiler\Prf\Site\Models\User;

class OrderPolicy
{

    public function schedule(User $user, Order $order)
    {
        return ($user->admin == 1  || $user->hasPermission('admin_shedule'))
            && $order->user->hasGuid()
            && $order->contragent->hasOrganization()
            && $order->can_schedule();
    }

    /**
     * Determine whether the user can view the post.
     *
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function view(User $user, Order $order)
    {
        //return $user->id == $order->user_id;   
        return (in_array($order->user->region_id,$user->notifiRegions->pluck('id')->toArray()) && $user->hasRole('ferroli_user')) 
        || $user->id == $order->user_id || $user->admin == 1 || $user->hasPermission('admin_users_view') || $user->hasPermission('admin_order_view');
    
    }

    
    public function distributor(User $user, Order $order)
    {
        return $user->hasPermission('distributors') && $user->distributors()->pluck('id')->contains($order->getAttribute('id'));
    }


    /**
     * Determine whether the user can create posts.
     *
     * @param  User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->id > 0;
    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  User $user
     * @param  Order $order
     * @return bool
     */
    public function update(User $user, Order $order)
    {
        return $user->getAttribute('admin') == 1 || $user->hasPermission('admin_order_update');
    }

    /**
     * @param  User $user
     * @param  Order $order
     * @return bool
     */
    public function message(User $user, Order $order)
    {
        return
            $user->getAttribute('id') == $order->getAttribute('user_id')
            || $order->address->addressable->id == $user->getAttribute('id');
    }

}
