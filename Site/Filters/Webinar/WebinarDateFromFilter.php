<?php

namespace ServiceBoiler\Prf\Site\Filters\Webinar;

use Illuminate\Support\Collection;
use ServiceBoiler\Repo\Filters\FerroliSingleDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class WebinarDateFromFilter extends DateFilter
{

    use FerroliSingleDate;

    protected $render = true;
    protected $search = 'date_from';

    public function label()
    {
        return trans('site::admin.webinar.datetime');
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
        return 'webinars.datetime';
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