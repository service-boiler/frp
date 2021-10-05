<?php

namespace ServiceBoiler\Prf\Site\Filters\Page;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'pages.h1' => 'ASC'
        ];
    }

}