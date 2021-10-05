<?php

namespace ServiceBoiler\Prf\Site\Filters\UserPrereg;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class InvitedSelectFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'invited';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'invited';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return 'Приглашен';
    }
}