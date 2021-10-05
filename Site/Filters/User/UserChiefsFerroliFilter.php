<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use ServiceBoiler\Repo\Filters\BootstrapCheckbox;

class UserChiefsFerroliFilter extends Filter
{
    use BootstrapCheckbox;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->where('admin', 1)->orWhereHas('roles',function ($query){
                $query->whereIn('name', ['ferroli_user','supervisor','admin_tender_view']);
            });

        return $builder;
    }

}