<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\AcademyVideo;
use ServiceBoiler\Prf\Site\Models\User;


class AcademyVideoPolicy
{

 
   public function delete(User $user, AcademyVideo $video)
    {
        if($video->stages->count() > 0){;
        return false;
        } else {
        return true;
        }
    }


}
