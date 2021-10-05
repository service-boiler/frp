<?php

namespace ServiceBoiler\Prf\Site\Filters\RetailOrder;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class RetailOrderSortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'retail_orders.created_at' => 'DESC'
        ];
    }
}