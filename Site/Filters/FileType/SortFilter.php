<?php

namespace ServiceBoiler\Prf\Site\Filters\FileType;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'file_types.sort_order' => 'ASC'
        ];
    }

}