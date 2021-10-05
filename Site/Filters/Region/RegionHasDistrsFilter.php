<?php

namespace ServiceBoiler\Prf\Site\Filters\Region;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class RegionHasDistrsFilter extends Filter
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
                        ->where('display', 1)
                        ->whereHas('roles', function ($q) {$q->whereIn('name',config('site.roles_distr'));});
                });
        });

        return $builder;
    }

}