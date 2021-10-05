<?php

namespace ServiceBoiler\Prf\Site\Filters\Organization;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'organizations.name' => 'ASC'
        ];
    }
}