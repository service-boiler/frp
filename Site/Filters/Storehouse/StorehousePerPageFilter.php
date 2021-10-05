<?php

namespace ServiceBoiler\Prf\Site\Filters\Storehouse;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class StorehousePerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.storehouse', 100)];
    }
}