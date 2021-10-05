<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Promocode;
use ServiceBoiler\Prf\Site\Models\User;

class PromocodePolicy
{

 
   public function delete(User $user, Promocode $promocode)
    {
        return $promocode->users()->count() == 0;
    }


}
