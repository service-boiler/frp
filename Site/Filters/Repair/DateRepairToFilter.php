<?php

namespace ServiceBoiler\Prf\Site\Filters\Repair;

use ServiceBoiler\Repo\Filters\BootstrapDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class DateRepairToFilter extends DateFilter
{

    use BootstrapDate;

    protected $render = true;
    protected $search = 'date_repair_to';

    public function label()
    {
        return trans('site::repair.placeholder.date_repair_to');
    }

    /**
     * @return string
     */
    public function column()
    {
        return 'repairs.date_repair';
    }

    protected function operator()
    {
        return '<=';
    }


}