<?php

namespace ServiceBoiler\Prf\Site\Filters\Trade;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class TradePerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.trade', 10)];
    }
}