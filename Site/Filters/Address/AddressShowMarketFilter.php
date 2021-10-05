<?php

namespace ServiceBoiler\Prf\Site\Filters\Address;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class AddressShowMarketFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->where('show_market_ru', 1);
    }

}