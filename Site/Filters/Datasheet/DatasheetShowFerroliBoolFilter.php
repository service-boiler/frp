<?php

namespace ServiceBoiler\Prf\Site\Filters\Datasheet;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class DatasheetShowFerroliBoolFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'show_ferroli';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'datasheets.show_ferroli';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::messages.show_ferroli');
    }
}