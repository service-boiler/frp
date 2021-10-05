<?php

namespace ServiceBoiler\Prf\Site\Filters\Address;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class FerroliManagerAddressFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $user=auth()->user();
        $notification_address = $user->email;
        $notifiRegions = $user->notifiRegions;
        if(!($user->hasRole('admin_site') 
            || $user->hasPermission('admin_users_view')
            || $user->hasPermission('admin_addresses_view')
            || $user->hasPermission('admin_addresses_update')) ) {
        
            $builder = $builder->where('addressable_type', '=', 'users')
            ->where( function ($query) use ($notifiRegions,$user) {
                    $query->whereHas('user',function ($query) use ($notifiRegions,$user) {
                
                            $query->whereHas('addresses',function ($query) use ($notifiRegions) {
                                $query->whereHas('region', function ($query) use ($notifiRegions) {
                                    $query->whereIn('region_id',$notifiRegions->pluck('id'));
                                })->orWhereIn('region_id',$notifiRegions->pluck('id')) ;
                            })->orWhere('created_by',$user->id);
                    });
                });
           
            
           /*  $builder = $builder->whereIn('region_id',$notifiRegions->pluck('id'))
                ->where('id','!=',$user->id); */
            
           /*  dump($builder->toSql());
            dd($builder->getBindings()); */
            
        }
        return $builder;
    }
}