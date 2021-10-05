<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\RetailOrder\RetailOrderSortFilter;
use ServiceBoiler\Prf\Site\Models\RetailOrder;

class RetailOrderRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return RetailOrder::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            RetailOrderSortFilter::class,
//            MountingDateCreatedFromFilter::class,
//            MountingDateCreatedToFilter::class,
        ];
    }
}