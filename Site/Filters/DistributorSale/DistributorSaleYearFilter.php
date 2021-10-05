<?php

namespace ServiceBoiler\Prf\Site\Filters\DistributorSale;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;


class DistributorSaleYearFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        return ['' => trans('site::messages.select_no_matter'),'2020'=>'2020','2021'=>'2021'] ;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'year';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'year';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::distributor_sales.filter.year');
    }

}