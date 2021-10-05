<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\PartnerPlusRequest;
use ServiceBoiler\Prf\Site\Models\User;

class PartnerPlusRequestPolicy
{

    public function view(User $user, PartnerPlusRequest $partnerPlusRequest)
    {
       return (in_array($user->id, [$partnerPlusRequest->user_id,
                                    $partnerPlusRequest->created_by_id,
                                    $partnerPlusRequest->distributor_id,
                                    $partnerPlusRequest->partner_id])
       
       || $user->hasRole('admin_site') || $user->hasRole('supervisor') || $user->hasRole('admin_ticket_subscribe') || $user->hasRole('admin_ticket_supervisor'));
    }

    public function comm(User $user, PartnerPlusRequest $partnerPlusRequest)
    {
       return $user->hasRole('admin_site') || $user->hasRole('supervisor') || $user->hasRole('ferroli_user') || $user->hasRole('admin_ticket_subscribe') || $user->hasRole('admin_ticket_supervisor');
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
     * @param  PartnerPlusRequest $partnerPlusRequest
     * @return bool
     */
    public function update(User $user, PartnerPlusRequest $partnerPlusRequest)
    {
        return (in_array($user->id, [$partnerPlusRequest->user_id,
                                    $partnerPlusRequest->created_by_id,
                                    $partnerPlusRequest->distributor_id,
                                    $partnerPlusRequest->partner_id])
       
       || $user->hasRole('admin_site') || $user->hasRole('supervisor') || $user->hasRole('admin_ticket_subscribe') || $user->hasRole('admin_ticket_supervisor'))
       && in_array($partnerPlusRequest->status_id,['1','9','10']);
    }

    /**
     * @param  User $user
     * @param  PartnerPlusRequest $partnerPlusRequest
     * @return bool
     */
    public function message(User $user, PartnerPlusRequest $partnerPlusRequest)
    {
        return
            $user->getAttribute('id') == $partnerPlusRequest->getAttribute('user_id')
            || $partnerPlusRequest->address->addressable->id == $user->getAttribute('id');
    }

}
