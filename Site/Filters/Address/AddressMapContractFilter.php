<?php

namespace ServiceBoiler\Prf\Site\Filters\Address;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class AddressMapContractFilter extends Filter
{


    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder
            ->whereHas('users', function ($query) {
                $query
                    ->whereHas('contragents',  function ($query) {
                        $query->where('contract','!=','');
                    });
              
            });

        return $builder;
    }


}