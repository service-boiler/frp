<?php

namespace ServiceBoiler\Prf\Site\Filters\PriceType;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EnabledFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->where("enabled", 1);
    }
}