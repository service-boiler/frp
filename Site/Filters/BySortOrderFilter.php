<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class BySortOrderFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            $this->table . '.sort_order' => 'ASC'
        ];
    }
}