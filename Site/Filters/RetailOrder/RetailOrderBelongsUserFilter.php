<?php

namespace ServiceBoiler\Prf\Site\Filters\Mounter;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class MounterBelongsUserFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {

        $builder = $builder->whereHas('userAddress', function ($address) {
            $address
                ->where('addressable_type', '=', 'users')
                ->where('addressable_id', '=', auth()->user()->getAuthIdentifier());
        });

        return $builder;
    }
}