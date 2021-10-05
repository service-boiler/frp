<?php

namespace ServiceBoiler\Prf\Site\Filters\Repair;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class RepairDateActFromFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'date_act_from';

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack() && $this->filled($this->search)) {
            $builder->whereHas('act', function ($query) {
                //$query->where($this->column(), $this->operator(), $this->get($this->search) . ' 00:00:00');
				$query->where($this->column(), $this->operator(), date('Y-m-d', strtotime($this->get($this->search))));
            });
        }

        return $builder;
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

    public function label()
    {
        return trans('site::repair.help.date_act');
    }

    protected function placeholder()
    {
        return trans('site::repair.placeholder.date_from');
    }

    protected function attributes()
    {
        return parent::attributes()->merge(['style' => 'width:100px;']);
    }


}