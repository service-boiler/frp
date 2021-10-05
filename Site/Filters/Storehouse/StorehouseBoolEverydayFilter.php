<?php

namespace ServiceBoiler\Prf\Site\Filters\Storehouse;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class StorehouseBoolEverydayFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'everyday';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'storehouses.everyday';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::storehouse.everyday');
    }
}