<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbUserRequest;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EsbUserRequestUserEsbFilter extends Filter
{
 

    function apply($builder, RepositoryInterface $repository)
    {

        $builder = $builder->where('esb_user_id', auth()->user()->id);
        
        //dump($builder->toSql());
        //dd($builder->getBindings());

        return $builder;
    }

   

}