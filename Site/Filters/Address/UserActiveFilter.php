<?php

namespace ServiceBoiler\Prf\Site\Filters\Address;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class UserActiveFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->whereHas('users', function ($query) {
            $query->where('active', 1);
        });

        return $builder;
    }

}