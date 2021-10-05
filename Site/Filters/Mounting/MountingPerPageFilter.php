<?php

namespace ServiceBoiler\Prf\Site\Filters\Mounting;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class MountingPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.mounting', 10)];
    }
}