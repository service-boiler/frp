<?php

namespace ServiceBoiler\Prf\Site\Filters\EventType;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'event_types.sort_order' => 'ASC'
        ];
    }

}