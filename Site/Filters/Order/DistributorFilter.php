<?php

namespace ServiceBoiler\Prf\Site\Filters\Order;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class DistributorFilter extends Filter
{


    function apply($builder, RepositoryInterface $repository)
    {

        return $builder->whereHas('address', function ($query) {
            $query
                ->where('type_id', 6)
                ->where('addressable_id', Auth::user()->getAuthIdentifier())
                ->where('addressable_type', DB::raw('"users"'));
        });


    }

}