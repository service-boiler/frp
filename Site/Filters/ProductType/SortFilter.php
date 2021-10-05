<?php

namespace ServiceBoiler\Prf\Site\Filters\ProductType;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'product_types.name' => 'ASC'
        ];
    }
}