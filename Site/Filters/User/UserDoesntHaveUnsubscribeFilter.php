<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class UserDoesntHaveUnsubscribeFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->doesntHave("unsubscribe");

    }
}