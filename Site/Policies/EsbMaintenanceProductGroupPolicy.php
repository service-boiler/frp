<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\EsbMaintenanceProductGroup;

class EsbMaintenanceProductGroupPolicy
{


    /**
     * @param  User $user
     * @param  Difficulty $difficulty
     * @return bool
     */
    public function delete(User $user, EsbMaintenanceProductGroup $esbMaintenanceProductGroup)
    {
        return $user->admin == 1 && $esbMaintenanceProductGroup->products()->count() == 0;
    }


}
