<?php

namespace ServiceBoiler\Prf\Site\Filters\Announcement;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortDateFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'announcements.date' => 'DESC'
        ];
    }

}