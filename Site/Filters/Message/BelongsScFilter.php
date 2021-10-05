<?php

namespace ServiceBoiler\Prf\Site\Filters\Message;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use Illuminate\Support\Facades\Auth;

class BelongsScFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {

        $builder = $builder->where(function ($query) use ($repository){
            $query->orWhere($repository->getTable().'.user_id', Auth::user()->getAuthIdentifier());
            $query->orWhere($repository->getTable().'.receiver_id', Auth::user()->getAuthIdentifier());
        });
        return $builder;
    }
}