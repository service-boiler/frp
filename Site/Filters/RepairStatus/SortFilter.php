<?php

namespace ServiceBoiler\Prf\Site\Filters\RepairStatus;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'repair_statuses.sort_order' => 'ASC'
        ];
    }
}