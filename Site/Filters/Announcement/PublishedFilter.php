<?php

namespace ServiceBoiler\Prf\Site\Filters\Announcement;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class PublishedFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->where(config('site.check_field'), 1);
    }
}