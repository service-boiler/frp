<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class DisplaySelectFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'display';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'display';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::user.display');
    }
}