<?php

namespace ServiceBoiler\Prf\Site\Filters\AuthorizationType;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class AuthorizationTypeSortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'authorization_types.brand_id' => 'ASC', 'authorization_types.name' => 'ASC',
        ];
    }
}