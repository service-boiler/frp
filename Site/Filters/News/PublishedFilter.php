<?php

namespace ServiceBoiler\Prf\Site\Filters\News;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class PublishedFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->where("published", 1);
    }
}