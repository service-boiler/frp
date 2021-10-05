<?php

namespace ServiceBoiler\Prf\Site\Filters\Order;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class OrderPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.order', 10)];
    }
}