<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbRepair;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter;

class EsbRepairUserSearchFilter extends SearchFilter
{

    use BootstrapInput;

    protected $render = true;
    protected $search = 'search_user';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack()) {
            if (!empty($this->columns())) {
                $words = $this->split($this->get($this->search));
                if (!empty($words)) {

                    $builder->whereHas('esbProduct', function ($query) use ($words) {
                        $query->whereHas('client', function ($query) use ($words) {
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

        return $builder;
    }

    protected function columns()
    {
        return [
            'users.name',
            'users.name_for_site',
            'users.phone',
        ];
    }

    public function label()
    {
        return trans('site::user.placeholder.search');
    }

}