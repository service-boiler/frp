<?php

namespace ServiceBoiler\Prf\Site\Filters\Webinar;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortAscFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'webinars.datetime' => 'asc'
        ];
    }

}