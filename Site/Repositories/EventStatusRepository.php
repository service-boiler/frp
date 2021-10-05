<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\EventType\SortFilter;
use ServiceBoiler\Prf\Site\Models\EventStatus;

class EventStatusRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return EventStatus::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            SortFilter::class
        ];
    }
}