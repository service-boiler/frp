<?php

namespace ServiceBoiler\Prf\Site\Filters\Region;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class RegionCountryFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder
            ->join('countries', 'countries.id', '=', 'regions.countries_id')
            ->orderBy("countries.sort_order");

        return $builder;
    }

}