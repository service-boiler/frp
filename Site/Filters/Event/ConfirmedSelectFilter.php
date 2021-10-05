<?php

namespace ServiceBoiler\Prf\Site\Filters\Event;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class ConfirmedSelectFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'confirmed';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'confirmed';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::event.confirmed');
    }
}