<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbUserProduct;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EsbUserProductOwnerArchiveFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        
        if(auth()->user()->type_id !=4 ) {
            $builder = $builder
                ->where(function($q) {
                    $q->whereNull('service_id')
                        ->orWhere('service_id','<>',auth()->user()->id);
                    
                })
                
                ->whereHas('esbUser', function($q) 
                {
                    $q->whereHas('esbServices', function($q) 
                        {   
                            $q->where('service_id',auth()->user()->id);
                        
                        })->where('type_id',4)
                          ->orWhere('created_by',auth()->user()->id);
            }) ->orWhere(function ($q) {
                
                $q->whereHas('repairs', function($q) {$q->where('repairs.user_id',auth()->user()->id);})
                ->where('esb_user_products.enabled',0);});
        } else {
            $builder = $builder->where('user_id', auth()->user()->id)->whereEnabled(0);
            
        }
          
    
        return $builder;
    }
}