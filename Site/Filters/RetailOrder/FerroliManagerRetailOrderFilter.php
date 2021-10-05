<?php

namespace ServiceBoiler\Prf\Site\Filters\RetailOrder;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class FerroliManagerRetailOrderFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $notification_address = auth()->user()->email;
        $notifiRegions = auth()->user()->notifiRegions;
        if(!auth()->user()->hasRole('admin_site') && !auth()->user()->hasRole('supervisor') ) {
        
            $builder = $builder->where( function ($query) use ($notifiRegions) {
                                        $query->whereHas('userAddress',function ($query) use ($notifiRegions) {
                                    
                                                    $query->whereHas('region', function ($query) use ($notifiRegions) {
                                                        $query->whereIn('region_id',$notifiRegions->pluck('id'));
                                                    });
                                               
                                                });
                                                
                                        $query->orWhereHas('userAddress',function ($query) {
                                                    $query->whereHas('user', function ($query) {
                                                        $query->where('created_by',auth()->user()->id);
                                                    });
                                                });
                                        });
            
        }
        return $builder;
    }
}