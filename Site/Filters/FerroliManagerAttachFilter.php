<?php

namespace ServiceBoiler\Prf\Site\Filters;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class FerroliManagerAttachFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $notification_address = auth()->user()->email;
        $notifiRegions = auth()->user()->notifiRegions;
        if(!auth()->user()->hasRole('admin_site') && !auth()->user()->hasRole('supervisor') && !auth()->user()->hasPermission('admin_users_view') ) {
        /* 
            $builder = $builder->where('id','!=',auth()->user()->id)
                ->where( function ($query) use ($notifiRegions) {
            
                $query->whereHas('addresses',function ($query) use ($notifiRegions) {
                    $query->whereHas('region', function ($query) use ($notifiRegions) {
                        $query->whereIn('region_id',$notifiRegions->pluck('id'));
                    })->orWhereIn('region_id',$notifiRegions->pluck('id'))
                      ->orWhere('created_by',auth()->user()->id) ;
                });
                }); */
            $builder = $builder->where('id','!=',auth()->user()->id)
                ->where( function ($query) use ($notifiRegions) {
            
                $query->whereIn('region_id',$notifiRegions->pluck('id'))->orWhere('created_by',auth()->user()->id) ;
                
                });
           
            
           /*  $builder = $builder->whereIn('region_id',$notifiRegions->pluck('id'))
                ->where('id','!=',auth()->user()->id); */
            
           /*  dump($builder->toSql());
            dd($builder->getBindings()); */
            
        }
        return $builder;
    }
}

