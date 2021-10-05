<?php

namespace ServiceBoiler\Prf\Site\Filters\Repair;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class RepairPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.repair', 10)];
    }
}