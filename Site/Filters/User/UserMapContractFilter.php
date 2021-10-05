<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class UserMapContractFilter extends Filter
{

       function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder
            ->whereHas('contragents',  function ($query) {
                        $query->where('contract','!=','');
                    });
       
//dd($builder->toSql());
        return $builder;
    }

   

}