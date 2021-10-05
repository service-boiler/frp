<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Authorization;
use ServiceBoiler\Prf\Site\Models\User;

class AuthorizationPolicy
{

    /**
     * Determine whether the user can view the post.
     *
     * @param User $user
     * @param Authorization $authorization
     * @return bool
     */
    public function view(User $user, Authorization $authorization)
    {
        return (in_array($authorization->user->region_id,$user->notifiRegions->pluck('id')->toArray()) 
                        && $user->hasRole('ferroli_user'))  
                    || $user->id == $authorization->user_id 
                    || $user->admin == 1 
                    || $user->hasRole('admin_site') 
                    || $user->hasRole('supervisor') 
                    || $user->hasRole('service_super')
                    || $user->hasPermission('admin_authorizations_view');
    
    }

    
   
    public function update(User $user, Authorization $authorization)
    {
        return (in_array($authorization->user->region_id,$user->notifiRegions->pluck('id')->toArray()) 
                        && $user->hasRole('ferroli_user'))  
                    || $user->id == $authorization->user_id 
                    || $user->admin == 1 
                    || $user->hasRole('supervisor') 
                    || ( $user->hasRole('service_super') && in_array($authorization->role->authorization_role->id,[1,4]))
                    || $user->hasPermission('admin_authorizations_update');
    
    }

  

}
