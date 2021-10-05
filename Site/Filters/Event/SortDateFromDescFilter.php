<?php

namespace ServiceBoiler\Prf\Site\Filters\Event;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortDateFromDescFilter extends OrderByFilter
{
    public function defaults(): array
    {
        return [
            'events.date_from' => 'DESC'
        ];
    }

}