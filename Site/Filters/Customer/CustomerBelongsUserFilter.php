<?php

namespace ServiceBoiler\Prf\Site\Filters\Customer;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class CustomerBelongsUserFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $user=auth()->user();
        
        if(!$user->hasRole('aдмин') && !$user->getAttribute('admin') == 1
            && !$user->hasRole('supervisor') && !$user->hasPermission('admin_tender_view')
            && !$user->hasRole('ferroli_user') 
            && !$user->hasPermission('admin_tender_view_comleted') ) {
            $builder=$builder->where('customers.manager_id', $user->getAuthIdentifier())
                ->whereNotNull('manager_id');
            
        }

        return $builder;
    }
}