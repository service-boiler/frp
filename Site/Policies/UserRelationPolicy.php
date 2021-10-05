<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\UserRelation;

class UserRelationPolicy
{

 

    /**
     * Determine whether the user can update the userRelation.
     *
     * @param  User $user
     * @param  UserRelation $userRelation
     * @return bool
     */
    public function edit(User $user, UserRelation $userRelation)
    {
        return ($user->id == $userRelation->parent_id || $user->getAttribute('admin') == 1 || $user->hasRole('ferroli_user') == 1);
    }

    /**
     * Determine whether the user can delete the userRelation.
     *
     * @param  User $user
     * @param  UserRelation $userRelation
     * @return bool
     */
    public function delete(User $user, UserRelation $userRelation)
    {
        return ($user->id == $userRelation->parent_id || $user->id == $userRelation->child_id || $user->getAttribute('admin') == 1);
    }


}
