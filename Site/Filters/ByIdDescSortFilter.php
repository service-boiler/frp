<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class ByIdDescSortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            $this->table . '.id' => 'DESC'
        ];
    }
}