<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use ServiceBoiler\Repo\Filters\BootstrapCheckbox;

class UserIsFlFilter extends Filter
{
    use BootstrapCheckbox;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder->where('type_id', 3);

        return $builder;
    }

}