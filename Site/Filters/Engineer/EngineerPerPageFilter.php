<?php

namespace ServiceBoiler\Prf\Site\Filters\Engineer;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class EngineerPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.engineer', 10)];
    }
}