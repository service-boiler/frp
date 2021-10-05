<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\Trade;

class TradePolicy
{

    /**
     * Determine whether the user can view the post.
     *
     * @param User $user
     * @param Trade $trade
     * @return bool
     */
    public function view(User $user, Trade $trade)
    {
        return $user->id == $trade->user_id;
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
     * @param  Trade $trade
     * @return bool
     */
    public function edit(User $user, Trade $trade)
    {
        return $user->id == $trade->user_id;
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  User $user
     * @param  Trade $trade
     * @return bool
     */
    public function delete(User $user, Trade $trade)
    {
        return ($user->id == $trade->user_id) && $trade->repairs()->count() == 0 && $trade->mountings()->count() == 0;
    }


}
