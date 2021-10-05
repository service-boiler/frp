<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbUserVisit;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EsbUserVisitUserEsbFilter extends Filter
{
 

    function apply($builder, RepositoryInterface $repository)
    {

        $builder = $builder->where('client_user_id', auth()->user()->id);
        
        return $builder;
    }

   

}