<?php

namespace ServiceBoiler\Prf\Site\Filters\Event;

use Illuminate\Support\Collection;
use ServiceBoiler\Repo\Filters\FerroliSingleDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class EventDateToFilter extends DateFilter
{

    use FerroliSingleDate;

    protected $render = true;
    protected $search = 'date_to';

    public function label()
    {
        return trans('site::event.date');
    }

    /**
     * @return string
     */
    protected function placeholder()
    {
        return trans('site::messages.date_to');
    }

    /**
     * @return string
     */
    public function column()
    {
        return 'events.date_from';
    }

    protected function operator()
    {
        return '<=';
    }

    /**
     * @return Collection
     */
    protected function attributes()
    {
        return parent::attributes()->merge(['style' => 'width:90px;']);
    }

}