<?php

namespace ServiceBoiler\Prf\Site\Filters\Region;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class SelectFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->where('country_id', config('site.country', 643));

        /**
         * ->whereHas('countries', function ($query) {
        $query->where('countries.enabled', 1);
        })
         */
        return $builder;
    }

}