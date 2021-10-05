<?php

namespace ServiceBoiler\Prf\Site\Filters\Event;

use Carbon\Carbon;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EventIndexDateFromFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
       
            $builder = $builder->where('events.date_to', '>',  Carbon::now()->subDay(3));
            
            return $builder;
        
    }
}