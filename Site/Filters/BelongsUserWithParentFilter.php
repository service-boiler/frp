<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class BelongsUserWithParentFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $user=auth()->user();
        $parent=$user->parent;
        $builder = $builder->where($repository->getTable().'.user_id', $user->getAuthIdentifier());
        if($user->parent){
        $builder = $builder->orWhere($repository->getTable().'.user_id', $user->parent->getAuthIdentifier());
        }
                            
        return $builder;
    }
}