<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class ShowSecondFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->where(config('site.check_field_second'), 1);
    }
}