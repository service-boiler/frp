<?php

namespace ServiceBoiler\Prf\Site\Filters\Tender;


use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;


class TenderDateToFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'created_to';

    public function label()
    {
        return 'Дата по';
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
        return '<=';
    }


}