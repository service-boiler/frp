<?php

namespace ServiceBoiler\Prf\Site\Filters\Mounting;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class MountingSocialEnabledFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'social_enabled';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'social_enabled';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::mounting.social_enabled');
    }
}