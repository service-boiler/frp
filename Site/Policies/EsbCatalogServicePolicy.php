<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\EsbCatalogService;

class EsbCatalogServicePolicy
{
    public function delete(User $user, EsbCatalogService $service)
    {
        return ($user->company()->id == $service->company_id);
    }
}
