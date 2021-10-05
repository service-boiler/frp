<?php

namespace ServiceBoiler\Prf\Site\Filters\Act;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class ActSortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'acts.created_at' => 'DESC'
        ];
    }
}