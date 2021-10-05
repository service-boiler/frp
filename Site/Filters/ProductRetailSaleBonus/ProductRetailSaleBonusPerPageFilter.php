<?php

namespace ServiceBoiler\Prf\Site\Filters\ProductRetailSaleBonus;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class ProductRetailSaleBonusPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.retail_sale_bonus', 10)];
    }
}