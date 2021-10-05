<?php

namespace ServiceBoiler\Prf\Site\Filters\Launch;

use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter as BaseFilter;

class SearchFilter extends BaseFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_launch';

    public function label()
    {
        return trans('site::launch.placeholder.search');
    }

    protected function columns()
    {
        return [
            'launches.name',
            'launches.address',
            'launches.phone',
            'launches.document_name',
            'launches.document_number',
            'launches.document_who',
        ];
    }

    protected function attributes()
    {
        $attributes = parent::attributes();
        $attributes->put('style', 'min-width: 208px;');

        return $attributes;
    }
}