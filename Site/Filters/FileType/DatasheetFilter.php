<?php

namespace ServiceBoiler\Prf\Site\Filters\FileType;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class DatasheetFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->whereGroupId(2);
        return $builder;
    }

}