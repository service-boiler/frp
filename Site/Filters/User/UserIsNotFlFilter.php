<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use ServiceBoiler\Repo\Filters\BootstrapCheckbox;

class UserIsNotFlFilter extends Filter
{
    use BootstrapCheckbox;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->where(function ($query) {
        $query->where('type_id', '<>', 3)
                ->orWhere('type_id', null);
                });
              

        return $builder;
    }

}