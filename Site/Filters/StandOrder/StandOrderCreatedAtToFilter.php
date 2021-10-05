<?php

namespace ServiceBoiler\Prf\Site\Filters\StandOrder;

use Illuminate\Support\Collection;
use ServiceBoiler\Repo\Filters\BootstrapTempusDominusDate;
use ServiceBoiler\Repo\Filters\DateFilter;

class StandOrderCreatedAtToFilter extends DateFilter
{

    use BootstrapTempusDominusDate;

    protected $render = true;
    protected $search = 'date_created_to';

    /**
     * @return string
     */
    public function label()
    {
        return trans('site::stand_order.created_at');
    }

    /**
     * @return string
     */
    public function column()
    {
        return 'stand_orders.created_at';
    }

    /**
     * @return string
     */
    protected function placeholder()
    {
        return trans('site::messages.date_to');
    }

    /**
     * @return Collection
     */
    protected function attributes()
    {
        return parent::attributes()->merge(['style' => 'width:100px;']);
    }

    /**
     * @return string
     */
    protected function operator()
    {
        return '<=';
    }


}