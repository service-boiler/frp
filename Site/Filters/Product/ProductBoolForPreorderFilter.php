<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class ProductBoolForPreorderFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'for_preorder';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'for_preorder';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::product.for_preorder');
    }
}
