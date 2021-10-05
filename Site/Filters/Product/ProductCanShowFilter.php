<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class ProductCanShowFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {

        $builder = $builder
            ->where(config('site.check_field'), 1)
            ->where('enabled', 1)
            ->where('service', 0);

        return $builder;
    }

}