<?php

namespace ServiceBoiler\Prf\Site\Filters\Equipment;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortByMenuSortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'equipments.sort_order_menu' => 'ASC'
        ];
    }

}