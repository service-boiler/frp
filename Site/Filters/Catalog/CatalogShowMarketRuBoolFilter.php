<?php

namespace ServiceBoiler\Prf\Site\Filters\Catalog;

use ServiceBoiler\Repo\Filters\BooleanFilter;
use ServiceBoiler\Repo\Filters\BootstrapSelect;

class CatalogShowMarketRuBoolFilter extends BooleanFilter
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

        return 'catalogs.show_market_ru';

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