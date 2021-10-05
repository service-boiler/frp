<?php

namespace ServiceBoiler\Prf\Site\Filters\Storehouse;

use Illuminate\Support\Collection;
use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class StorehouseDateUploadedFromFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'date_uploaded_from';

    public function label()
    {
        return trans('site::storehouse.uploaded_at');
    }

    /**
     * @return string
     */
    public function column()
    {
        return 'storehouses.uploaded_at';
    }

    /**
     * @return string
     */
    protected function placeholder()
    {
        return trans('site::messages.date_from');
    }

    /**
     * @return Collection
     */
    protected function attributes()
    {
        return parent::attributes()->merge(['style' => 'width:100px;']);
    }

    /**
     * @return string
     */
    protected function operator()
    {
        return '>=';
    }


}