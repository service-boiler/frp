<?php

namespace ServiceBoiler\Prf\Site\Filters\Region;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class RegionManagerAttachFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        if(!auth()->user()->hasRole('admin_site') && !auth()->user()->hasRole('supervisor') && !auth()->user()->hasPermission('admin_users_view') ) {
            $builder = $builder->whereHas('managers', function ($q)
            {
                $q->where('users.id',auth()->user()->id);
            });
        }

        return $builder;
    }

}