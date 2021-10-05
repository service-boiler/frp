<?php

namespace ServiceBoiler\Prf\Site\Filters\Message;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'messages.created_at' => 'DESC'
        ];
    }
}