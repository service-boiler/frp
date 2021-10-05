<?php

namespace ServiceBoiler\Prf\Site\Filters\Authorization;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class AuthorizationSortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'authorizations.created_at' => 'DESC'
        ];
    }
}