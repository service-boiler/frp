<?php

namespace ServiceBoiler\Prf\Site\Filters\Webinar;

use Carbon\Carbon;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class WebinarIndexDateFromFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
       
            $builder = $builder->where('webinars.datetime', '>',  Carbon::now()->subDay(3));
            return $builder;
        
    }
    
    
   

}