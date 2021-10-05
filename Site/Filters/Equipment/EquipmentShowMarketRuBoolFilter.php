<?php

namespace ServiceBoiler\Prf\Site\Filters\Equipment;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class EquipmentShowMarketRuBoolFilter extends BooleanFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * @return string
     */
    public function name(): string
    {
        return 'show_market_ru';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'equipments.show_market_ru';

    }

    public function defaults(): array
    {
        return [];
    }

    public function label()
    {
        return trans('site::messages.show_market_ru');
    }
}