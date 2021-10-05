<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbUserVisit;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EsbUserVisitServiceEsbFilter extends Filter
{
 

    function apply($builder, RepositoryInterface $repository)
    {

        $builder = $builder->where('service_user_id', auth()->user()->id);
        
        //dump($builder->toSql());
        //dd($builder->getBindings());

        return $builder;
    }

   

}