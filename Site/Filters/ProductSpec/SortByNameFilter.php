<?php

namespace ServiceBoiler\Prf\Site\Filters\ProductSpec;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class SortByNameFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->orderBy("product_specs.name");
    }

}