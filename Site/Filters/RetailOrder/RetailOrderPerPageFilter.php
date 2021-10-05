<?php

namespace ServiceBoiler\Prf\Site\Filters\RetailOrder;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class RetailOrderPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.retail_order', 10)];
    }
}