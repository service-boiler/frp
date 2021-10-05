<?php

namespace ServiceBoiler\Prf\Site\Filters\WebinarTheme;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'webinar_themes.name' => 'ASC'
        ];
    }

}