<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbCatalogService;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EsbCatalogServiceOwnerFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        

            $builder = $builder->whereIn('company_id',[auth()->user()->company()->id,'1']);

    
        return $builder;
    }
}