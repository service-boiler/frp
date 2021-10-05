<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Member;
use ServiceBoiler\Prf\Site\Models\User;

class MemberPolicy
{


    /**
     * Determine whether the user can delete the address.
     *
     * @param  User $user
     * @param  Member $member
     * @return bool
     */
    public function event(User $user, Member $member)
    {
        return is_null($member->getAttribute('event_id'));
    }


}
