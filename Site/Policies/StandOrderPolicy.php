<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\StandOrder;
use ServiceBoiler\Prf\Site\Models\User;

class StandOrderPolicy
{

    public function view(User $user, StandOrder $standOrder)
    {
        return $user->id == $standOrder->user_id;
    }

    public function distributor(User $user, StandOrder $standOrder)
    {
        return $user->hasPermission('distributors') && $user->distributors()->pluck('id')->contains($standOrder->getAttribute('id'));
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
     * @param  StandOrder $standOrder
     * @return bool
     */
    public function update(User $user, StandOrder $standOrder)
    {
        return $user->getAttribute('admin') == 1 || $user->id == $standOrder->user_id;
    }

    /**
     * @param  User $user
     * @param  StandOrder $standOrder
     * @return bool
     */
    public function message(User $user, StandOrder $standOrder)
    {
        return
            $user->getAttribute('id') == $standOrder->getAttribute('user_id')
            || $standOrder->address->addressable->id == $user->getAttribute('id');
    }

}
