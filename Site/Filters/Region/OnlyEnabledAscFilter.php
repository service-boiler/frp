<?php

namespace ServiceBoiler\Prf\Site\Filters\Region;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class OnlyEnabledAscFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->whereHas('addresses', function ($query) {
            $query
                ->where('addresses.type_id', 2)
                ->whereHas('users', function ($query) {
                    $query->where('users.display', 1);
                });
        });

        return $builder;
    }

}