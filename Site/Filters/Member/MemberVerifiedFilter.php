<?php

namespace ServiceBoiler\Prf\Site\Filters\Member;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class MemberVerifiedFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->where("verified", 1);
    }
}