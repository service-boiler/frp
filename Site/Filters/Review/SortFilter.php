<?php

namespace ServiceBoiler\Prf\Site\Filters\Review;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'reviews.created_at' => 'DESC'
        ];
    }
}