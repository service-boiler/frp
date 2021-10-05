<?php

namespace ServiceBoiler\Prf\Site\Filters\Region;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class OnlyEnabledUserFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->whereHas('addresses', function ($query) {
            $query
                ->where('type_id', 2)
                ->where('active', 1)
                ->whereHas('users', function ($query) {
                    $query
                        ->where('active', 1)
                        ->where('display', 1);
                });
        });

        return $builder;
    }

}