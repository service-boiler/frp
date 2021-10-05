<?php

namespace ServiceBoiler\Prf\Site\Filters\Member;

use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class DateFromFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'date_from';

    public function label()
    {
        return trans('site::messages.date').' '.trans('site::member.date_from');
    }

    /**
     * @return string
     */
    public function column()
    {
        return 'members.date_to';
    }

    protected function operator()
    {
        return '>=';
    }


}