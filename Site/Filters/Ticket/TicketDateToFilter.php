<?php

namespace ServiceBoiler\Prf\Site\Filters\Ticket;


use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;


class TicketDateToFilter extends DateFilter
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
        return 'tickets.created_at';
    }

    protected function operator()
    {
        return '<=';
    }


}