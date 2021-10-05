<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Region\SortFilter;
use ServiceBoiler\Prf\Site\Models\Region;

class RegionRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Region::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [
            SortFilter::class
        ];
    }
}