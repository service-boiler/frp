<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\BySortOrderFilter;
use ServiceBoiler\Prf\Site\Models\EsbMaintenanceDistance;

class EsbMaintenanceDistanceRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return EsbMaintenanceDistance::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [
            BySortOrderFilter::class
        ];
    }
}