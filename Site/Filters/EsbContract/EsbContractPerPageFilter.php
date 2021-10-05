<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbContract;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class EsbContractPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.contract', 100)];
    }
}