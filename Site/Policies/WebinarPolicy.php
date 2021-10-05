<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Webinar;
use ServiceBoiler\Prf\Site\Models\User;

class WebinarPolicy
{

 
   public function delete(User $user, Webinar $webinar)
    {
        return $webinar->usersVisits()->count() == 0;
    }


}
