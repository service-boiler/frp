<?php

namespace ServiceBoiler\Prf\Site\Filters\District;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'districts.sort_order' => 'ASC'
        ];
    }
}