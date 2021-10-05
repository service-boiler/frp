<?php

namespace ServiceBoiler\Prf\Site\Filters\DistributorSale;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class DistributorSaleBelongsUserFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {

        $builder = $builder->where('distributor_sales.user_id', auth()->user()->getAuthIdentifier());
        return $builder;
    }
}