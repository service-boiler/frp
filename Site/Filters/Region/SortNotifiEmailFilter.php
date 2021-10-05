<?php

namespace ServiceBoiler\Prf\Site\Filters\Region;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class SortNotifiEmailFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder

            ->orderBy('notification_address');

        return $builder;
    }

}