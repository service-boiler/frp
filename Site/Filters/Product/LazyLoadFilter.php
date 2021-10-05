<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class LazyLoadFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->with(['type', 'brand', 'prices', 'analogs', 'details', 'relations', 'images']);
        return $builder;
    }

}