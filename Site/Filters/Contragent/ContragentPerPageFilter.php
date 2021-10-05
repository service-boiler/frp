<?php

namespace ServiceBoiler\Prf\Site\Filters\Contragent;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class ContragentPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.contragent', 10)];
    }
}