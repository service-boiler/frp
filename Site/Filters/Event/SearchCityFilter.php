<?php

namespace ServiceBoiler\Prf\Site\Filters\Event;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter as BaseFilter;

class SearchCityFilter extends BaseFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_city';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack()) {
            if (!empty($this->columns())) {
                $words = $this->split($this->get($this->search));
                if (!empty($words)) {
                    foreach ($words as $word) {
                        $builder = $builder->where(function ($query) use ($word) {
                            foreach ($this->columns() as $column) {
                                $query->orWhereRaw("LOWER({$column}) LIKE LOWER(?)", ["%{$word}%"]);
                            }
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
            'events.city',
            'events.address'
        ];
    }

    public function label()
    {
        return trans('site::event.placeholder.search_address');
    }

    public function tooltip()
    {
        return false;
    }

}