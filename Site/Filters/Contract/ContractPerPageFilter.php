<?php

namespace ServiceBoiler\Prf\Site\Filters\Contract;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class ContractPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.contract', 10)];
    }
}