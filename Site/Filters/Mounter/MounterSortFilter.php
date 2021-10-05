<?php

namespace ServiceBoiler\Prf\Site\Filters\Mounter;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class MounterSortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'mounters.created_at' => 'DESC'
        ];
    }
}