<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class FerroliManagerFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        
        if(!auth()->user()->hasRole('admin_site') && !auth()->user()->hasRole('supervisor')  && !auth()->user()->hasPermission('admin_users_view') ) {
        $builder = $builder->where($repository->getTable().'.ferroli_manager_id', auth()->user()->getAuthIdentifier());
        }
        return $builder;
    }
}