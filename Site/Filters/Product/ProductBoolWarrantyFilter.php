<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class ProductBoolWarrantyFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'warranty';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'warranty';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::product.warranty');
    }
}