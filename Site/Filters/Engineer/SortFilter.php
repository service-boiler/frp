<?php

namespace ServiceBoiler\Prf\Site\Filters\Engineer;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'engineers.name' => 'ASC'
        ];
    }

}