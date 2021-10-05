<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EnabledFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->where($repository->getTable().'.enabled', 1);
        return $builder;
    }
}