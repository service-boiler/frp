<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class ActiveSelectFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'active';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'active';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::user.active');
    }
}