<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use ServiceBoiler\Repo\Filters\SearchFilter;

class ProductBoilerVolumeMinFilter extends SearchFilter
{


    protected $render = true;
    protected $search = 'boiler_vol_min';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack()) {
            $val = $this->get($this->search);
            if (!empty($val) && $val!='undefined') {

                $builder = $builder->whereHas('specRelations', function ($query) use ($val) {
                        $query->where('product_spec_id', 44)->where('spec_value_dec','>=',$val);
                    });
            }
        }

        return $builder;

    }

}