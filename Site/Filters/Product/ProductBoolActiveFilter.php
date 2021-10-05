<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class ProductBoolActiveFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'active';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'active';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::product.active');
    }
}