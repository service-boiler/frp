<?php

namespace ServiceBoiler\Prf\Site\Filters\Region;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'regions.name' => 'ASC'
        ];
    }
}