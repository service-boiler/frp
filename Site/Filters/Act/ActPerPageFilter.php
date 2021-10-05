<?php

namespace ServiceBoiler\Prf\Site\Filters\Act;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class ActPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.act', 10)];
    }
}