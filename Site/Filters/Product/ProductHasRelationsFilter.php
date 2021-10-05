<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\HasFilter;

class ProductHasRelationsFilter extends HasFilter
{

    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'has_details';
    }

    /**
     * @return string
     */
    public function relation(): string
    {
        return 'details';
    }

    /**
     * @return string
     */
    public function label()
    {
        return trans('site::product.help.has_details');
    }
}