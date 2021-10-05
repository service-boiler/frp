<?php

namespace ServiceBoiler\Prf\Site\Filters\Launch;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class LaunchPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.launch', 10)];
    }
}