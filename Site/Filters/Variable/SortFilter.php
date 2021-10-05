<?php

namespace ServiceBoiler\Prf\Site\Filters\Variable;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'variables.name' => 'ASC'
        ];
    }

}