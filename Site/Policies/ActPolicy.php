<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Act;
use ServiceBoiler\Prf\Site\Models\User;

class ActPolicy
{

    public function schedule(User $user, Act $act)
    {
        return $user->admin == 1
            && $act->user->hasGuid()
            && $act->can_schedule();
    }

    /**
     * Determine whether the user can view the post.
     *
     * @param User $user
     * @param Act $act
     * @return bool
     */
    public function view(User $user, Act $act)
    {
        return $user->getAttribute('admin') == 1 || $user->hasPermission('admin_acts') == 1 || $user->id == $act->getAttribute('user_id');
    }

    public function pdf(User $user, Act $act)
    {
        return $this->view($user, $act) && $user->hasPermission('acts.pdf');
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
     * @param  Act $act
     * @return bool
     */
    public function update(User $user, Act $act)
    {
        return $user->getAttribute('admin') == 1 || $user->hasPermission('admin_acts') == 1 || $user->id == $act->getAttribute('user_id');
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  User $user
     * @param  Act $act
     * @return bool
     */
    public function delete(User $user, Act $act)
    {
        return ($user->hasPermission('admin.roles')  || $user->hasPermission('admin_acts') == 1) && empty($act->guid) && empty($act->received) ;
    }


}
