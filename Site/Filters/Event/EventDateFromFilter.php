<?php

namespace ServiceBoiler\Prf\Site\Filters\Event;

use Illuminate\Support\Collection;
use ServiceBoiler\Repo\Filters\FerroliSingleDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class EventDateFromFilter extends DateFilter
{

    use FerroliSingleDate;

    protected $render = true;
    protected $search = 'date_from';

    public function label()
    {
        return trans('site::event.date');
    }

    /**
     * @return string
     */
    protected function placeholder()
    {
        return trans('site::messages.date_from');
    }

    /**
     * @return string
     */
    public function column()
    {
        return 'events.date_to';
    }

    protected function operator()
    {
        return '>=';
    }

    /**
     * @return Collection
     */
    protected function attributes()
    {
        return parent::attributes()->merge(['style' => 'width:90px;']);
    }

}