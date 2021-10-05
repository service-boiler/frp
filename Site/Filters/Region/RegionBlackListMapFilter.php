<?php

namespace ServiceBoiler\Prf\Site\Filters\Region;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class RegionBlackListMapFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->whereHas('warehouses', function ($query) {
            $query
                ->where('is_eshop', 1)
                ->where('active', 1)
                ->where(config('site.check_field'), 1)
                ->whereNotNull('web')
                ->whereHas('users', function ($query) {
                    $query
                        ->where('display', 1)
                        ->where('active', 1)
                        ->whereHas('roles', function ($query) {
                            $query->where('name', 'eshop');
                        });
                });
        });

        return $builder;
    }

}