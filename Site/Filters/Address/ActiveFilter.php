<?php

namespace ServiceBoiler\Prf\Site\Filters\Address;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class ActiveFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->where('active', 1);
    }

}