<?php


namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class UserVerifiedFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {

        $builder = $builder->where('users.verified', 1);

        return $builder;
    }
}

