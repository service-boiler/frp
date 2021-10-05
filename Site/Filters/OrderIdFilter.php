<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;

class OrderIdFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_order_id';

    public function label()
    {
        return trans('site::order.id');
    }

    protected function columns()
    {
        return [
            'orders.id'
        ];
    }

    protected function attributes()
    {
        $attributes = parent::attributes();
        $attributes->put('style', 'min-width: 208px;');

        return $attributes;
    }

}