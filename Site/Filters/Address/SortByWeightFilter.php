<?php

namespace ServiceBoiler\Prf\Site\Filters\Address;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class SortByWeightFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->orderBy("addresses.sort_order");
    }

}