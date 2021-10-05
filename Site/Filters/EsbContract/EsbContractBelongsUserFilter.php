<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbContract;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EsbContractBelongsUserFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {

        if(!auth()->user()->hasRole(config('site.supervisor_roles'),[])) {
            $builder = $builder->whereHas('contragent', function ($address) {
                $address->where('user_id', '=', auth()->user()->getAuthIdentifier());
            });
        }
        return $builder;
    }
}