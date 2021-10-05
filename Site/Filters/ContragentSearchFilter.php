<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;

class ContragentSearchFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_contragent';

    public function label()
    {
        return trans('site::contragent.placeholder.search');
    }

    protected function columns()
    {
        return [
            'contragents.name',
            'contragents.inn',
            'contragents.ogrn',
            'contragents.rs',
        ];
    }

    protected function attributes()
    {
        $attributes = parent::attributes();
        $attributes->put('style', 'min-width: 208px;');

        return $attributes;
    }

}