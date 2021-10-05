<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbCatalogPrice;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EsbCatalogPriceOwnerFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        

            $builder = $builder->where('company_id',auth()->user()->company()->id);

    
        return $builder;
    }
}