<?php

namespace ServiceBoiler\Prf\Site\Filters\Contract;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class ContractBelongsUserFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {

        $builder = $builder->whereHas('contragent', function ($address) {
            $address->where('user_id', '=', auth()->user()->getAuthIdentifier());
        });

        return $builder;
    }
}