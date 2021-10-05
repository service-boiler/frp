<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EsbUserServiceFilter extends Filter
{

    //Показываем только физ.лиц с маркета привязанных к сервису и созданным им.

    function apply($builder, RepositoryInterface $repository)
    {
        
        //if(!auth()->user()->hasRole('admin_site') && !auth()->user()->hasRole('supervisor')  && !auth()->user()->hasPermission('admin_users_view') ) {
        $builder = $builder->whereHas('esbServices', function($q) {$q->where('service_id',auth()->user()->id);})
                ->where('type_id',4)
                ->orWhere('created_by',auth()->user()->id);
        
        return $builder;
    }
}