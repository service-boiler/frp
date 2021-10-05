<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;

class ProductSearchEmptyFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_product';

    function apply($builder, RepositoryInterface $repository)
    {
        if($this->get($this->search)) {
            if ($this->canTrack()) {
                if (!empty($this->columns())) {
                    $words = $this->split($this->get($this->search));
                    if (!empty($words)) {
                        foreach ($words as $product_id) {
                            $builder = $builder->whereHas('relations', function ($query) use ($product_id) {
                                $query->where(function ($query) use ($product_id) {
                                    $query->whereId($product_id);
                                });
                            });
                        }
                    } else{
                        $builder->whereRaw("false");
                    }
                }
            } else{
                $builder->whereRaw("false");
            }
        }
        return $builder;
    }

    protected function columns()
    {
        return [
            'serials.id',
        ];
    }

    public function label()
    {
        return trans('site::product.search_placeholder');
    }

}