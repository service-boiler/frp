<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class ProductBoolForSaleFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'forsale';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'forsale';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::product.forsale');
    }
}