<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class MountingFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->has("mounting_bonus");

        return $builder;
    }

}