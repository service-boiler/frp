<?php

namespace ServiceBoiler\Prf\Site\Filters\FileType;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class MountingFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->whereGroupId(1);
        return $builder;
    }

}