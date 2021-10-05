<?php

namespace ServiceBoiler\Prf\Site\Filters\Distance;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'distances.sort_order' => 'ASC'
        ];
    }

}