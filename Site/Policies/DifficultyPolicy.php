<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\Difficulty;

class DifficultyPolicy
{


    /**
     * @param  User $user
     * @param  Difficulty $difficulty
     * @return bool
     */
    public function delete(User $user, Difficulty $difficulty)
    {
        return $user->admin == 1 && $difficulty->repairs()->count() == 0;
    }


}
