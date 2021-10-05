<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\UserRelation;
use ServiceBoiler\Prf\Site\Models\UserFlRoleRequest;

class UserFlRoleRequestPolicy
{

 

    public function edit(User $user, UserFlRoleRequest $UserFlRoleRequest)
    {
        //return ($user->id == $UserFlRoleRequest->parent_id || $user->getAttribute('admin') == 1 || $user->hasRole('ferroli_user') == 1);
        return 1;
    }

    
    public function delete(User $user, UserFlRoleRequest $UserFlRoleRequest)
    {
        return ($user->id == $userRelation->parent_id || $user->id == $userRelation->child_id || $user->getAttribute('admin') == 1);
    }


}
