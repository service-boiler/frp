<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class WithPhonesFilter extends Filter
{

    /**
     * @param $builder
     * @param RepositoryInterface $repository
     * @return mixed
     */
    function apply($builder, RepositoryInterface $repository)
    {

        $builder = $builder->with('contacts.phones');

        return $builder;
    }

}