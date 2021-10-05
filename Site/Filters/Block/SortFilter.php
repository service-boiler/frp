<?php

namespace ServiceBoiler\Prf\Site\Filters\Block;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'blocks.sort_order' => 'ASC'
        ];
    }

}