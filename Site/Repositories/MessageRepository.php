<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Message\SearchFilter;
use ServiceBoiler\Prf\Site\Filters\Message\SortFilter;
use ServiceBoiler\Prf\Site\Models\Message;

class MessageRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Message::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            SortFilter::class,
            SearchFilter::class
        ];
    }
}