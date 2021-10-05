<?php

namespace ServiceBoiler\Prf\Site\Filters\Mounting;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class MountingSortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'mountings.created_at' => 'DESC'
        ];
    }
}