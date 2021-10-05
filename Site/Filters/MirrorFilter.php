<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class MirrorFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->where($repository->getTable().'.'.config('site.defaults.mirror'), 1);
        return $builder;
    }

}