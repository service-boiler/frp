<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Certificate;
use ServiceBoiler\Prf\Site\Models\User;

class CertificatePolicy
{

    /**
     * Determine whether the user can view the engineer.
     *
     * @param User $user
     * @param Certificate $certificate
     * @return bool
     */
    public function view(User $user, Certificate $certificate)
    {  
        if((!empty($certificate->engineer) && $user->id == $certificate->engineer->user_id) || (!empty($certificate->user) && $user->id == $certificate->user_id))
        {
        return true;
        }
        else {return false;}
    }

}
