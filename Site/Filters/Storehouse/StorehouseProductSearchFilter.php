<?php

namespace ServiceBoiler\Prf\Site\Filters\Storehouse;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;

class StorehouseProductSearchFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_sh_prod';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack()) {
            if (!empty($this->columns())) {
                $words = $this->split($this->get($this->search));
                if (!empty($words)) {
                    $builder = $builder->whereHas('itemProducts', function ($query) use ($words) {
                        $query->where(function ($query) use ($words) {
                            foreach ($words as $word) {
                                $query->where(function ($query) use ($word) {
                                    foreach ($this->columns() as $column) {
                                        $query->orWhereRaw("LOWER({$column}) LIKE LOWER(?)", ["%{$word}%"]);
                                    }
                                });
                            }
                        });
                    });
                }
            }
        }
        
        //dd($builder->getBindings());
        return $builder;
    }

    protected function columns()
    {
        return [
            'products.sku',
            'products.name',
        ];
    }

    public function label()
    {
        return trans('site::product.product');
    }

   

}