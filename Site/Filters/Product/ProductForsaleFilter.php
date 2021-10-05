<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class ProductForsaleFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->where('forsale', 1);
    }
}