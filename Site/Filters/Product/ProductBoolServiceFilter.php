<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class ProductBoolServiceFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'service';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'service';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::product.service');
    }
}