<?php

namespace ServiceBoiler\Prf\Site\Filters\Address;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class SortByRegionFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {

        return $builder->orderBy("addresses.region_id");

    }

}