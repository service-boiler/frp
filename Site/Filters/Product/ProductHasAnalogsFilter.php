<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\HasFilter;

class ProductHasAnalogsFilter extends HasFilter
{

    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'has_analogs';
    }

    /**
     * @return string
     */
    public function relation(): string
    {
        return 'analogs';
    }

    /**
     * @return string
     */
    public function label()
    {
        return trans('site::product.help.has_analogs');
    }
}