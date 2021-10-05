<?php

namespace ServiceBoiler\Prf\Site\Filters\Repair;

use ServiceBoiler\Repo\Filters\BootstrapDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class DateNewsFromFilter extends DateFilter
{

    use BootstrapDate;

    protected $render = true;
    protected $search = 'date_news_from';

    public function label()
    {
        return trans('site::news.placeholder.date_news_from');
    }

    /**
     * @return string
     */
    public function column()
    {
        return 'news.date';
    }

    protected function operator()
    {
        return '>=';
    }


}