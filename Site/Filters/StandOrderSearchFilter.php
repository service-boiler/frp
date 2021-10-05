<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\SearchFilter;
use ServiceBoiler\Repo\Filters\BootstrapInput;

class StandOrderSearchFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;

    public function label()
    {
        return trans('site::stand_order.search_placeholder');
    }

    function apply($builder, RepositoryInterface $repository)
    {

        if ($this->canTrack()) {
            if (!empty($this->columns())) {
                $words = $this->split($this->get($this->search));
                if (!empty($words)) {
                    $builder = $builder->whereHas('items', function ($query) use ($words) {
                        $query->whereHas('product', function ($query) use ($words){
                            foreach ($words as $word) {
                                $query->where(function ($query) use ($word) {
                                    foreach ($this->columns() as $column){
                                        $query->orWhereRaw("LOWER({$column}) LIKE LOWER(?)", ["%{$word}%"]);
                                    }
                                });
                            }
                        });

                    });
                }
            }
        }

        return $builder;
    }

    protected function columns()
    {
        return [
            'products.name',
            'products.sku'
        ];
    }

}