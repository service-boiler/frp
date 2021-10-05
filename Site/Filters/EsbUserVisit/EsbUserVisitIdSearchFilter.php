<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbUserVisit;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter as BaseFilter;

class EsbUserVisitIdSearchFilter extends BaseFilter
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
            }
        }

        return $builder;
    }

    protected function columns()
    {
        return [
            'esb_user_visits.id',
        ];
    }

    public function label()
    {
        return trans('site::user.esb_user_visit.search_id');
    }

    protected function attributes()
    {
        return parent::attributes()->merge(['style' => 'width:80px;']);
    }

}