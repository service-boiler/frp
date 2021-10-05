<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbUserRequest;

use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class EsbUserRequestDateFromFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'date_planned_from';

    public function label()
    {
        return trans('site::user.esb_request.date_planned') .' <i class="ml-2 fa fa-calendar"></i>';
    }

    protected function placeholder()
    {
        return trans('site::messages.date_from');
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
        return 'esb_user_requests.date_planned';
    }

    protected function operator()
    {
        return '>=';
    }


}