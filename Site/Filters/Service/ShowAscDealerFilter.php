<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class ShowAscDealerFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {

        if ($this->canTrack() && $this->filled('show')) {
            $checkboxes = $this->get('show');
            $builder = $builder->whereHas('roles', function ($query) use ($checkboxes) {
                $query->where(function ($query) use ($checkboxes) {
                    if (in_array('asc', $checkboxes)) {
                        $query->orWhere('name', "asc");
                    }
                    if (in_array('dealer', $checkboxes)) {
                        $query->orWhere('name', "dealer");
                    }
                });

            });

        } else{
            $builder = $builder->whereRaw('false');
        }
//        dump($builder->getBindings());
//        dd($builder->toSql());
        return $builder;
    }
}