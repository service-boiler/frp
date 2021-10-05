<?php

namespace ServiceBoiler\Prf\Site\Filters\Address;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class AddressActiveBoolFilter extends BooleanFilter
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

        return 'addresses.active';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::address.active');
    }
}