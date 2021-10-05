<?php

namespace ServiceBoiler\Prf\Site\Filters\Trade;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'trades.name' => 'ASC'
        ];
    }

}