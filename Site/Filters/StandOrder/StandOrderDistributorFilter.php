<?php

namespace ServiceBoiler\Prf\Site\Filters\StandOrder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class StandOrderDistributorFilter extends Filter
{


    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->whereIn('status_id',config('site.distr_stand_order_status'))->whereHas('warehouse_address', function ($query) {
            $query
                ->where('addressable_id', Auth::user()->getAuthIdentifier())
                ->where('addressable_type', DB::raw('"users"'));
        });


    }

}