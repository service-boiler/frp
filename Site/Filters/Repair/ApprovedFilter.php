<?php

namespace ServiceBoiler\Prf\Site\Filters\Repair;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class ApprovedFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder
            ->where("repairs.status_id", 5)
            ->join("users", "repairs.user_id", '=', "users.id")
            ->orderBy("users.name");

        return $builder;
    }
}