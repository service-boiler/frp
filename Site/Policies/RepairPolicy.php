<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Repair;
use ServiceBoiler\Prf\Site\Models\User;

class RepairPolicy
{

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

    public function pdf(User $user, Repair $repair)
    {
        return $repair->getAttribute('status_id') == 5
            && $this->view($user, $repair) && $user->hasPermission('repairs.pdf');
    }

    /**
     * Determine whether the user can view the post.
     *
     * @param User $user
     * @param Repair $repair
     * @return bool
     */
    public function view(User $user, Repair $repair)
    {
        //return $user->getAttribute('admin') == 1 || $user->id == $repair->getAttribute('user_id');
        return (in_array($repair->user->region_id,$user->notifiRegions->pluck('id')->toArray()) && $user->hasPermission('repairs_admin_view')) || 
                $user->id == $repair->user_id || $user->admin == 1 || $user->hasRole('supervisor') || 
                $user->hasPermission('admin_repairs') || $user->hasPermission('repairs_admin_view');
    
    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  User $user
     * @param  Repair $repair
     * @return bool
     */
    public function update(User $user, Repair $repair)
    {
        return $user->getAttribute('admin') == 1 || $user->id == $repair->user_id || $user->hasPermission('admin_repairs');
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  User $user
     * @param  Repair $repair
     * @return bool
     */
    public function delete(User $user, Repair $repair)
    {
        return false;
    }


}
