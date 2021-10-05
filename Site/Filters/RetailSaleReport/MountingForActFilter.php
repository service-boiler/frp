<?php

namespace ServiceBoiler\Prf\Site\Filters\Mounting;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class MountingForActFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder
            ->where("mountings.status_id", 2)
            ->whereNull('act_id')
            //->orderBy("mountings.contragent_id", 'ASC')
            ->orderBy("mountings.created_at", 'DESC');

        return $builder;
    }
}