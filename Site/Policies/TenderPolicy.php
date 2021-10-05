<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Tender;
use ServiceBoiler\Prf\Site\Models\User;

class TenderPolicy
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

    public function pdf(User $user, Tender $tender)
    {
        return $tender->getAttribute('status_id') == 5
            && $this->view($user, $tender) && $user->hasPermission('tenders.pdf');
    }

    /**
     * Determine whether the user can view the post.
     *
     * @param User $user
     * @param Tender $tender
     * @return bool
     */
    public function view(User $user, Tender $tender)
    {
        //return $user->getAttribute('admin') == 1 || $user->id == $tender->getAttribute('user_id');
        return (in_array($tender->region_id,$user->notifiRegions->pluck('id')->toArray()) && $user->hasPermission('tenders_admin_view')) || $user->id == $tender->user_id || $user->admin == 1 || $user->hasRole('supervisor');
    
    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  User $user
     * @param  Tender $tender
     * @return bool
     */
    public function update(User $user, Tender $tender)
    {
        
        return ($user->getAttribute('admin') == 1 || $user->id == $tender->manager_id ) && in_array($tender->status_id ,['1','9']);
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  User $user
     * @param  Tender $tender
     * @return bool
     */
    public function delete(User $user, Tender $tender)
    {
        return false;
    }


}
