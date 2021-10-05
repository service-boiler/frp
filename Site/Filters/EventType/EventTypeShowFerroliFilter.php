<?php

namespace ServiceBoiler\Prf\Site\Filters\EventType;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EventTypeShowFerroliFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->where('show_ferroli', 1);
    }

}