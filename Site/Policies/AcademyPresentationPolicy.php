<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\AcademyPresentation;
use ServiceBoiler\Prf\Site\Models\User;


class AcademyPresentationPolicy
{

 
   public function delete(User $user, AcademyPresentation $presentation)
    {   
    if($presentation->stages->count() > 0){;
        return false;
        } else {
        return true;
        }
    }


}
