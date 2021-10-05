<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbUserVisit;

use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class DateEsbUserVisitToFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'date_planned_to';

    protected function placeholder()
    {
        return trans('site::messages.date_to');
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
        return 'esb_user_visits.date_planned';
    }

    protected function operator()
    {
        return '<=';
    }


}