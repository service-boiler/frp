<?php

namespace ServiceBoiler\Prf\Site\Filters\Repair;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter as BaseFilter;

class RepairIdSearchFilter extends BaseFilter
{
    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_id';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack()) {
            if (!empty($this->columns())) {
                $words = $this->split($this->get($this->search));
                if (!empty($words)) {
                    foreach ($words as $word) {
                        $builder = $builder->where(function ($query) use ($word) {
                            foreach ($this->columns() as $column) {
                                $query->orWhereRaw("LOWER({$column}) = LOWER(?)", ["{$word}"]);
                            }
                        });
                    }
                }
//                else{
//                    $builder->whereRaw("false");
//                }
            }
        }

        return $builder;
    }

    protected function columns()
    {
        return [
            'repairs.id',
        ];
    }

    public function label()
    {
        return trans('site::repair.placeholder.search_act');
    }

    public function tooltip()
    {
        return trans('site::repair.help.search_act');
    }


}