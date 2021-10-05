<?php

namespace ServiceBoiler\Prf\Site\Filters\Datasheet;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class DatasheetPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.datasheet', 10)];
    }
}