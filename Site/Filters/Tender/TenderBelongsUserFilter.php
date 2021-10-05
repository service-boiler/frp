<?php

namespace ServiceBoiler\Prf\Site\Filters\Tender;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class TenderBelongsUserFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        $user=auth()->user();
        if(!$user->hasRole('aдмин') && !$user->getAttribute('admin') == 1 && !$user->hasRole('supervisor') && !$user->hasPermission('admin_tender_view') && !$user->hasPermission('admin_tender_view_comleted') ) {
            $builder = $builder->where('tenders.manager_id', $user->getAuthIdentifier());
            
        }
        if($user->hasPermission('admin_tender_view_comleted')) {
            $builder = $builder->whereIn('tenders.status_id',config('site.tender_status_approved'));
            }
        return $builder;
    }
}