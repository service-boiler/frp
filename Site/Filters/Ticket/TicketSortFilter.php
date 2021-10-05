<?php

namespace ServiceBoiler\Prf\Site\Filters\Ticket;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class TicketSortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'tickets.created_at' => 'DESC'
        ];
    }
}