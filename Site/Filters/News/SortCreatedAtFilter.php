<?php

namespace ServiceBoiler\Prf\Site\Filters\News;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortCreatedAtFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'news.created_at' => 'DESC'
        ];
    }

}