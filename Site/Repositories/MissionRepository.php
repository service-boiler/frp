<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\DateFromSortDescFilter;
use ServiceBoiler\Prf\Site\Filters\Mission\MissionDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Mission\MissionDateToFilter;
use ServiceBoiler\Prf\Site\Filters\Mission\MissionCreatedByFilter;
use ServiceBoiler\Prf\Site\Filters\Mission\MissionRegionFilter;
use ServiceBoiler\Prf\Site\Filters\Mission\MissionStatusFilter;
use ServiceBoiler\Prf\Site\Filters\Mission\MissionUserFilter;
use ServiceBoiler\Prf\Site\Models\Mission;

class MissionRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Mission::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {   
        return [
           
           DateFromSortDescFilter::class,
           MissionDateFromFilter::class,
           MissionDateToFilter::class,
           MissionRegionFilter::class,
           MissionCreatedByFilter::class,
           MissionStatusFilter::class,
           MissionUserFilter::class,
           //MissionSearchFilter::class,
        ];
    }
}
