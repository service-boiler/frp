<?php

namespace ServiceBoiler\Prf\Site\Filters\Repair;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\HasFilter;

class RepairIsFoundSerialFilter extends HasFilter
{

    use BootstrapSelect;

    protected $render = true;


    /**
     * @return string
     */
    public function name(): string
    {
        return 'is_found';
    }

    public function relation(): string
    {
        return 'serial';
    }

    public function label()
    {
        return trans('site::repair.help.is_found');
    }
}