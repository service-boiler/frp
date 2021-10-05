<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class ByNameSortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            $this->table . '.name' => 'ASC'
        ];
    }
}