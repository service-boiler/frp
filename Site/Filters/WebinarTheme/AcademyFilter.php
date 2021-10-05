<?php

namespace ServiceBoiler\Prf\Site\Filters\Variable;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class AcademyFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->whereIn('name', config('site.variables.academy'));
    }
}