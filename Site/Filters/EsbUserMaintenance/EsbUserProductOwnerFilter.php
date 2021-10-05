<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbUserProduct;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EsbUserProductOwnerFilter extends Filter
{

    //Показываем только физ.лиц с маркета привязанных к сервису и созданным им.

    function apply($builder, RepositoryInterface $repository)
    {
        
        if(auth()->user()->type_id !=4 ) {
            $builder = $builder->whereHas('esbUser', function($q) 
                {
                    $q->whereHas('esbServices', function($q) 
                        {   
                            $q->where('service_id',auth()->user()->id);
                        
                        })->where('type_id',4)
                          ->orWhere('created_by',auth()->user()->id);
            });
        } else {
            $builder = $builder->where('user_id', auth()->user()->id);
            
        }
              //  ->where('type_id',4)
              //  ->orWhere('created_by',auth()->user()->id);
        
    
        return $builder;
    }
}