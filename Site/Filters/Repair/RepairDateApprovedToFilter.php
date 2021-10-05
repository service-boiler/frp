<?php

namespace ServiceBoiler\Prf\Site\Filters\Repair;

use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class RepairDateApprovedToFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'date_repair_approved_to';

    public function label()
    {
        return trans('site::repair.date_approved');
    }

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
        return 'repairs.approved_at';
    }

    protected function operator()
    {
        return '<=';
    }


}