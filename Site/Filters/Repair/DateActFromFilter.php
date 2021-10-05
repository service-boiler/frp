<?php

namespace ServiceBoiler\Prf\Site\Filters\Repair;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class DateActFromFilter extends DateFilter
{

    use BootstrapDate;

    protected $render = true;
    protected $search = 'date_act_from';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack() && $this->filled($this->search)) {
            $builder->whereHas('act', function ($query) {
                $query->where($this->column(), $this->operator(), $this->get($this->search).' 00:00:00');
            });
        }
        return $builder;
    }

    public function label()
    {
        return trans('site::repair.placeholder.date_act_from');
    }

    /**
     * @return string
     */
    public function column()
    {
        return 'acts.created_at';
    }

    protected function operator()
    {
        return '>=';
    }


}