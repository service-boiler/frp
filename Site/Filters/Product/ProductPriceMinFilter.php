<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;
use ServiceBoiler\Prf\Site\Models\Currency;

class ProductPriceMinFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'price_min';


    function apply($builder, RepositoryInterface $repository)
    {
        
        if ($this->canTrack()) {
                $val = $this->get($this->search);
                if (!empty($val)) {
                    
                        $builder = $builder->whereHas('prices', function ($query) use ($val) {
                            $query->where(function ($query) use($val) {
                            $query->whereIn('type_id',[config('site.defaults.guest.price_promo_rub_type_id'),config('site.defaults.guest.price_rub_type_id')])->where('price','>',0)->where('price','>=',$val);
                            })->orWhere(function ($query) use($val) {
                                $query->whereIn('type_id',[config('site.defaults.guest.price_promo_eur_type_id'),config('site.defaults.guest.price_type_id')])->where('price','>',0)->where('price','>=',$val/Currency::find(978)->rates);
                            });}


                            );

                } 
        } 

        return $builder;
    }


}