<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class ProductSortOrderFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'products.sort_order' => 'ASC'
        ];
    }

}