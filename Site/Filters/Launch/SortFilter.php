<?php

namespace ServiceBoiler\Prf\Site\Filters\Launch;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'launches.name' => 'ASC'
        ];
    }

}