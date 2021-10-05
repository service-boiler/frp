<?php

namespace ServiceBoiler\Prf\Site\Filters\Event;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class UserNotInEventParticipantFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    { 
    
        return $builder->whereDoesntHave('participants',function ($query){
                $query->where('participants.event_id', $this->get('event_id'));
            });
    }

}
