<?php

namespace ServiceBoiler\Prf\Site\Filters\Address;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class AddressPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.address', 10)];
    }
}