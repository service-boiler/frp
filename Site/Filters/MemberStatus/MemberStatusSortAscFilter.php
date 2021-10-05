<?php

namespace ServiceBoiler\Prf\Site\Filters\MemberStatus;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class MemberStatusSortAscFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'member_statuses.sort_order' => 'ASC'
        ];
    }

}