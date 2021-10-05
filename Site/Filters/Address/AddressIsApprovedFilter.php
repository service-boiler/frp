<?php

namespace ServiceBoiler\Prf\Site\Filters\Address;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class AddressIsApprovedFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->where('approved', 1);
    }

}