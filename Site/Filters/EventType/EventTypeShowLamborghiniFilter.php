<?php

namespace ServiceBoiler\Prf\Site\Filters\EventType;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EventTypeShowLamborghiniFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->where('show_lamborghini', 1);
    }

}