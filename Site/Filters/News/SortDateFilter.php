<?php

namespace ServiceBoiler\Prf\Site\Filters\News;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortDateFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'news.date' => 'DESC'
        ];
    }

}