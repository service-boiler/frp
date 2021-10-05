<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Filters\SearchFilter;
use ServiceBoiler\Repo\Filters\BootstrapInput;

class CatalogSearchFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_catalog';

    public function label()
    {
        return trans('site::catalog.placeholder.search');
    }

    protected function columns()
    {
        return [
            'name',
            'name_plural',
            'description',
        ];
    }

    protected function attributes()
    {
        $attributes = parent::attributes();
        $attributes->put('style', 'min-width: 208px;');
        return $attributes;
    }

}