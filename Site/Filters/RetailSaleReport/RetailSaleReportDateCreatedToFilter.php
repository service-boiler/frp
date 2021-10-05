<?php

namespace ServiceBoiler\Prf\Site\Filters\RetailSaleReport;

use Illuminate\Support\Collection;
use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class RetailSaleReportDateCreatedToFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'date_created_to';

    /**
     * @return string
     */
    public function label()
    {
        return trans('site::mounting.created_at');
    }

    /**
     * @return string
     */
    public function column()
    {
        return 'retail_sale_reports.created_at';
    }

    /**
     * @return string
     */
    protected function placeholder()
    {
        return trans('site::messages.date_to');
    }

    /**
     * @return Collection
     */
    protected function attributes()
    {
        return parent::attributes()->merge(['style' => 'width:100px;']);
    }

    /**
     * @return string
     */
    protected function operator()
    {
        return '<=';
    }


}