<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Ticket\TicketDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Ticket\TicketDateToFilter;
use ServiceBoiler\Prf\Site\Filters\Ticket\TicketStatusFilter;
use ServiceBoiler\Prf\Site\Filters\Ticket\TicketCreatedByFilter;
use ServiceBoiler\Prf\Site\Filters\Ticket\TicketReceiverFilter;
use ServiceBoiler\Prf\Site\Filters\Ticket\TicketSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Ticket\TicketRegionFilter;
use ServiceBoiler\Prf\Site\Models\Ticket;

class TicketRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Ticket::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {   
        return [
           
           TicketSearchFilter::class,
           TicketDateFromFilter::class,
           TicketDateToFilter::class,
           TicketStatusFilter::class,
           TicketCreatedByFilter::class,
           TicketReceiverFilter::class,
           TicketRegionFilter::class,

        ];
    }
}
