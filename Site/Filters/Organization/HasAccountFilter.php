<?php

namespace ServiceBoiler\Prf\Site\Filters\Organization;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class HasAccountFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->whereNotNull('account_id');
        return $builder;
    }

}