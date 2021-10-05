<?php

namespace ServiceBoiler\Prf\Site\Filters\Event;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EventRunnedFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->whereIn("status_id", [1,2,3] );
    }
}