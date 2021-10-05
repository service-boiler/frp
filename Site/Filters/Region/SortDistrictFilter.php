<?php

namespace ServiceBoiler\Prf\Site\Filters\Region;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class SortDistrictFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder
            ->join('districts', 'districts.id', '=', 'regions.district_id')
            ->orderBy("districts.sort_order");

        return $builder;
    }

}