<?php

namespace ServiceBoiler\Prf\Site\Filters\TicketTheme;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class SortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'ticket_themes.name' => 'ASC'
        ];
    }

}