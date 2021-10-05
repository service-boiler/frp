<?php

namespace ServiceBoiler\Prf\Site\Filters\CurrencyArchive;

use ServiceBoiler\Repo\Filters\BootstrapDate;
use ServiceBoiler\Repo\Filters\DateFilter as BaseFilter;

class DateFilter extends BaseFilter
{

    use BootstrapDate;

    protected $render = true;
    protected $search = 'search_date';


    public function label()
    {
        return trans('site::archive.placeholder.date');
    }

    /**
     * @return string
     */
    public function column()
    {
        return 'currency_archives.date';
    }


}