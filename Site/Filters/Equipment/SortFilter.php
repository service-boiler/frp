<?php

namespace ServiceBoiler\Prf\Site\Filters\Equipment;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'equipments.sort_order' => 'ASC'
        ];
    }

}