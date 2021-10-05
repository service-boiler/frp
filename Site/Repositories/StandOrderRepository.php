<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\StandOrder\StandOrderAddressSelectFilter;
use ServiceBoiler\Prf\Site\Filters\StandOrder\StandOrderCreatedAtFromFilter;
use ServiceBoiler\Prf\Site\Filters\StandOrder\StandOrderCreatedAtToFilter;
use ServiceBoiler\Prf\Site\Filters\StandOrder\StandOrderStatusFilter;
use ServiceBoiler\Prf\Site\Filters\StandOrder\StandOrderSearchFilter;

use ServiceBoiler\Prf\Site\Filters\OrderSortFilter;
use ServiceBoiler\Prf\Site\Models\StandOrder;

class StandOrderRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return StandOrder::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [
            OrderSortFilter::class,
            StandOrderStatusFilter::class,
            StandOrderCreatedAtFromFilter::class,
            StandOrderCreatedAtToFilter::class,
            StandOrderSearchFilter::class,
        ];
    }
}