<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;

class ProductSpecPowerMaxFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'pwr_max';

    function apply($builder, RepositoryInterface $repository)
    {
        
        if ($this->canTrack()) {
            $val = $this->get($this->search);
            $mount = $this->get('mount');
            $fuel = $this->get('fuel');

            if (!empty($val)) {
                if($val<24 && ($mount=='Настенный' || !$mount) && $fuel=='Газ') {
                    $builder = $builder->whereHas('specRelations', function ($query) use ($val) {
                        $query->where('product_spec_id', 1)->where(function ($q) use ($val){
                            $q->where('spec_value_dec', '<=', $val)
                                ->orWhere('spec_value_dec', '=', 24);
                        });

                    });
                } else {
                    $builder = $builder->whereHas('specRelations', function ($query) use ($val) {
                        $query->where('product_spec_id', 1)->where('spec_value_dec', '<=', $val);
                    });
                }
            } 
        }

        return $builder;
    }


}