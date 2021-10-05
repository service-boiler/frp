<?php

namespace ServiceBoiler\Prf\Site\Filters\Mission;


use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;


class MissionDateToFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'date_to';

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
        return 'missions.date_to';
    }

    protected function operator()
    {
        return '<=';
    }


}