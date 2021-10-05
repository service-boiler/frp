<?php

namespace ServiceBoiler\Prf\Site\Filters\Webinar;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use Illuminate\Support\Facades\Auth;

class WebinarTypePublicFilter extends Filter
{
    function apply($builder, RepositoryInterface $repository)
    {
        if(Auth::user() && Auth::user()->hasRole(['asc','csc','admin_site','dealer'],[])) {
            return $builder;
        } else {
            $builder = $builder->where('webinars.type_id', 1);
            return $builder;
        }
    }
}