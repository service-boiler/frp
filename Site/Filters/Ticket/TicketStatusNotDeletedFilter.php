<?php


namespace ServiceBoiler\Prf\Site\Filters\Ticket;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class TicketStatusNotDeletedFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->where('status_id','!=','6');
        return $builder;
    }
}