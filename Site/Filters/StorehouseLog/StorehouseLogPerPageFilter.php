<?php

namespace ServiceBoiler\Prf\Site\Filters\StorehouseLog;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class StorehouseLogPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.storehouse_log', 10)];
    }
}