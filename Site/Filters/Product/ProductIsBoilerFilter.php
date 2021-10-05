<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use ServiceBoiler\Repo\Filters\SearchFilter;

class ProductIsBoilerFilter extends SearchFilter
{


    protected $render = true;
    protected $search = 'is_boiler';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack()) {
                $val = $this->get($this->search);

            if ($val=='1') {
                        $builder = $builder->where("products.equipment_id", '81');
                }
            }
        return $builder;

    }

}