<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class HasApprovedRepairFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder
            ->whereHas("repairs", function($query){
                $query->whereStatusId(5)->whereNull('act_id');
            })
            ->orderBy("name");

        return $builder;
    }
}