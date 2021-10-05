<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbUserProduct;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class EsbUserProductNotEnabledFilter extends Filter
{

    //Показываем только физ.лиц с маркета привязанных к сервису и созданным им.

    function apply($builder, RepositoryInterface $repository)
    {
        
        //if(!auth()->user()->hasRole('admin_site') && !auth()->user()->hasRole('supervisor')  && !auth()->user()->hasPermission('admin_users_view') ) {
        $builder = $builder->whereEnabled('0');
        
    
        return $builder;
    }
}