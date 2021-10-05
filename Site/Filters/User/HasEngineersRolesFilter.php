<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class HasEngineersRolesFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {

        $builder = $builder->whereHas('roles', function ($query) {
            $query->whereIn('name', config('site.roles_engineer'));
        });

        return $builder;
    }
}