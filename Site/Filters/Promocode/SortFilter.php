<?php

namespace ServiceBoiler\Prf\Site\Filters\Promocode;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'promocodes.name' => 'ASC'
        ];
    }

}