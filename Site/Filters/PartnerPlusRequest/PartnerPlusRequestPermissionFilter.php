<?php

namespace ServiceBoiler\Prf\Site\Filters\PartnerPlusRequest;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class PartnerPlusRequestPermissionFilter extends Filter
{


    function apply($builder, RepositoryInterface $repository)
    {
        $notification_address = Auth()->user()->email;
        $notifiRegions = Auth()->user()->notifiRegions;
        $user=Auth()->user();
        if(!($user->hasRole('admin_site') || $user->hasRole('supervisor') || $user->hasRole('admin_ticket_subscribe') || $user->hasRole('admin_ticket_supervisor')) ) {
        
            $builder = $builder->where( function ($query) use ($notifiRegions) {
                    $query->whereHas('partner',function ($query) use ($notifiRegions) {
                
                                $query->whereHas('addresses',function ($query) use ($notifiRegions) {
                                    $query->whereHas('region', function ($query) use ($notifiRegions) {
                                        $query->whereIn('region_id',$notifiRegions->pluck('id'));
                                    })->orWhereIn('region_id',$notifiRegions->pluck('id')) ;
                                });
                            });
                })
                ->orWhere('created_by_id', $user->getAuthIdentifier())
                ->orWhere('partner_id', $user->getAuthIdentifier())
                ->orWhere('distributor_id', $user->getAuthIdentifier());;
          
            
        }
        
        
        return $builder;
        

    }

}