<?php

namespace ServiceBoiler\Prf\Site\Filters\DistributorSale;


use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;


class DistributorSaleDateToFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'date_sale_to';

    public function label()
    {
        return trans('site::distributor_sales.filter.date_sale_to');
    }

    protected function placeholder()
    {
        return '';
    }

    protected function attributes()
    {
        return parent::attributes()->merge(['style' => 'width:100px;']);
    }

    /**
     * @return string
     */
    public function column()
    {
        return 'distributor_sales.date_sale';
    }

    protected function operator()
    {
        return '<=';
    }


}