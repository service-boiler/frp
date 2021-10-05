<?php

namespace ServiceBoiler\Prf\Site\Filters\Contract;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class FerroliManagersContractFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $notification_address = auth()->user()->email;
        $notifiRegions = auth()->user()->notifiRegions;
       
        if(!auth()->user()->hasRole('admin_site') && !auth()->user()->hasRole('supervisor') && 
            !auth()->user()->hasPermission('admin_repairs')) {
        
            $builder = $builder->whereHas('contragent',function ($query) use ($notifiRegions) {
                                    $query->whereHas('addresses',function ($query) use ($notifiRegions) {
                                    $query->whereIn('region_id',$notifiRegions->pluck('id'));
                                });
                            });
                    
            
        }
        return $builder;
    }
}