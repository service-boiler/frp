<?php

namespace ServiceBoiler\Prf\Site\Filters\Service;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class DisplayFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->where(env('DB_PREFIX', '') . $repository->getTable() . '.display', 1);

        return $builder;
    }
}