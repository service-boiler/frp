<?php

namespace ServiceBoiler\Prf\Site\Filters\DigiftBonus;

use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class DigiftBonusDateToFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'date_bonus_to';

    public function label()
    {
        return trans('site::messages.created_at');
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
        return 'bonuses.created_at';
    }

    protected function operator()
    {
        return '<=';
    }


}