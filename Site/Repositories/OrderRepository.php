<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Order\OrderAddressSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Order\OrderCreatedAtFromFilter;
use ServiceBoiler\Prf\Site\Filters\Order\OrderCreatedAtToFilter;
use ServiceBoiler\Prf\Site\Filters\Order\OrderIdSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Order\OrderStatusFilter;
use ServiceBoiler\Prf\Site\Filters\OrderSearchFilter;

use ServiceBoiler\Prf\Site\Filters\OrderSortFilter;
use ServiceBoiler\Prf\Site\Models\Order;

class OrderRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Order::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [
            OrderSortFilter::class,
            OrderStatusFilter::class,
            OrderIdSearchFilter::class,
            OrderSearchFilter::class,
            OrderCreatedAtFromFilter::class,
            OrderCreatedAtToFilter::class,
        ];
    }
}