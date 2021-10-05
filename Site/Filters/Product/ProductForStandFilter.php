<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class ProductForStandFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->where('for_stand', 1);
    }
    
    
}