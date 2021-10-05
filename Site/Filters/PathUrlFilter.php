<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use Illuminate\Support\Facades\Route;

class PathUrlFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {       
        return $builder->where('path', Route::getCurrentRoute()->uri);
    }
}