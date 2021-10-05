<?php

namespace ServiceBoiler\Prf\Site\Filters\Serial;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class SerialPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.serial', 10)];
    }
}