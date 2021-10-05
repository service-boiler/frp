<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class UserHasDistributorSaleFilter extends Filter
{
    
    function apply($builder, RepositoryInterface $repository)
    {
        
        $builder = $builder->whereHas('distributorSales');
        
        return $builder;
    }
    
}