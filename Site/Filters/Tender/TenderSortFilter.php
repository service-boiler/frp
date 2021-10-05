<?php

namespace ServiceBoiler\Prf\Site\Filters\Tender;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class TenderSortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'tenders.created_at' => 'DESC'
        ];
    }
}