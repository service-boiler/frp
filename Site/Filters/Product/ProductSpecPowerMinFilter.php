<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;

class ProductSpecPowerMinFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'pwr_min';

    function apply($builder, RepositoryInterface $repository)
    {
        
        if ($this->canTrack()) {
                $val = $this->get($this->search);
                if (!empty($val)) {
                    
                        $builder = $builder->whereHas('specRelations', function ($query) use ($val) {
                            $query->where('product_spec_id',1)->where('spec_value_dec','>=',$val);
                            });
                        
                    
                } 
        }

        return $builder;
    }
    
}