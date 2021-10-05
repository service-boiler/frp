<?php

namespace ServiceBoiler\Prf\Site\Filters\DigiftBonus;

use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class DigiftBonusDateFromFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'date_bonus_from';

    public function label()
    {
        return trans('site::messages.created_at');
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
        return 'bonuses.created_at';
    }

    protected function operator()
    {
        return '>=';
    }


}