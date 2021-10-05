<?php

namespace ServiceBoiler\Prf\Site\Filters\Serial;

use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter as BaseFilter;

class SearchFilter extends BaseFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_serial';

    public function label()
    {
        return trans('site::serial.placeholder.search');
    }

    protected function columns()
    {
        return [
            'serials.id',
            'serials.product_id',
        ];
    }

    protected function attributes()
    {
        $attributes = parent::attributes();
        $attributes->put('style', 'min-width: 208px;');

        return $attributes;
    }
}