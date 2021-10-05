<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\Mission;

class MissionPolicy
{


    public function edit(User $user, Mission $mission)
    {   
        
        return ($user->id == $mission->created_by_user_id  
        || (!empty($mission->users) && in_array($user->id, $mission->users()->pluck('users.id')->toArray() )));
    }

    
    public function delete(User $user, Mission $mission)
    {   
        return $user->hasRole('supervisor');
    }


}
