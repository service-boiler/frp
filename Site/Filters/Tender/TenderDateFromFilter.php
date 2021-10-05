<?php

namespace ServiceBoiler\Prf\Site\Filters\Tender;


use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;


class TenderDateFromFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'created_from';

    public function label()
    {
        return 'Дата с';
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
        return 'tenders.created_at';
    }

    protected function operator()
    {
        return '>=';
    }


}