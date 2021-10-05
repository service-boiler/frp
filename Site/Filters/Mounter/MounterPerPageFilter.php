<?php

namespace ServiceBoiler\Prf\Site\Filters\Mounter;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class MounterPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.mounter', 10)];
    }
}