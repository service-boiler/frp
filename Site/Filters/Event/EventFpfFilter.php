<?php

namespace ServiceBoiler\Prf\Site\Filters\Event;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EventFpfFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->where("type_id", 2);
    }
}
