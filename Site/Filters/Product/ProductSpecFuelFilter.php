<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;

class ProductSpecFuelFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'fuel';

    function apply($builder, RepositoryInterface $repository)
    {
        
        if ($this->canTrack()) {
                $val = $this->get($this->search);
                if (!empty($val)) {
                    
                        $builder = $builder->whereHas('specRelations', function ($query) use ($val) {
                            $query->where('product_spec_id',100)->where('spec_value',$val);
                            });
                } 
        } 

        return $builder;
    }


}