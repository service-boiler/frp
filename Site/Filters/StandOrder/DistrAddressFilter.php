<?php

namespace ServiceBoiler\Prf\Site\Filters\Order;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;

class DistrAddressFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_da';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack()) {
            if (!empty($this->columns())) {
                $words = $this->split($this->get($this->search));
                if (!empty($words)) {
                    foreach ($words as $word) {
                        $builder->whereHas('address', function ($query) use ($word) {
                            $query->where(function ($query) use ($word) {
                                foreach ($this->columns() as $column) {
                                    $query->orWhereRaw("LOWER({$column}) LIKE LOWER(?)", ["%{$word}%"]);
                                }
                            });
                        });
                    }
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
        return trans('site::order.placeholder.search_da');
    }

    public function tooltip()
    {
        return trans('site::order.help.search_da');
    }

}
