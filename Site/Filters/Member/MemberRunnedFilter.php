<?php

namespace ServiceBoiler\Prf\Site\Filters\Member;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class MemberRunnedFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        return $builder
            ->whereIn("status_id", [2])
            ->where("verified", 1)
            ->orderBy('date_from', 'ASC');
    }
}
