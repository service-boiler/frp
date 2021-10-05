<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Webinar\SortFilter;
use ServiceBoiler\Prf\Site\Filters\Webinar\WebinarDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Webinar\WebinarDateToFilter;
use ServiceBoiler\Prf\Site\Filters\Webinar\SearchFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\WebinarRequest;
use ServiceBoiler\Prf\Site\Models\Webinar;

class WebinarRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Webinar::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [
            SearchFilter::class,
            WebinarDateFromFilter::class,
            WebinarDateToFilter::class,
            SortFilter::class,
            
        ];
    }
}