<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\EsbCatalogServicePrice;

class EsbCatalogServicePricePolicy
{
    public function edit(User $user, EsbCatalogServicePrice $price)
    {

        return ($user->company()->id == $price->company_id);
    }

    public function delete(User $user, EsbCatalogServicePrice $price)
    {

        return ($user->company()->id == $price->company_id);
    }
}
