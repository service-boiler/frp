<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbUserProduct;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;

class AddressSearchFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_address';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack()) {
            if (!empty($this->columns())) {
                $words = $this->split($this->get($this->search));
                if (!empty($words)) {

                    $builder->whereHas('address', function ($query) use ($words) {
                        foreach ($words as $word) {
                            $query->where(function ($query) use ($word) {
                                foreach ($this->columns() as $column) {
                                    $query->orWhereRaw("LOWER({$column}) LIKE LOWER(?)", ["%{$word}%"]);
                                }
                            });
                        }
                    });
                }
            }
        }

        return $builder;
    }

    protected function columns()
    {
        return [
            'addresses.name',
            'addresses.full',
        ];
    }

    public function label()
    {
        return trans('site::address.placeholder.search');
    }

}