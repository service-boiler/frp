<?php

namespace ServiceBoiler\Prf\Site\Filters\Engineer;

use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter as BaseFilter;

class SearchFilter extends BaseFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_engineer';

    public function label()
    {
        return trans('site::engineer.placeholder.search');
    }

    protected function columns()
    {
        return [
            'engineers.name',
            'engineers.address',
            'engineers.phone',
        ];
    }

    protected function attributes()
    {
        $attributes = parent::attributes();
        $attributes->put('style', 'min-width: 208px;');

        return $attributes;
    }
}