<?php

namespace ServiceBoiler\Prf\Site\Filters\Event;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortCreatedAtFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'events.created_at' => 'DESC'
        ];
    }

}