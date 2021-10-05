<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Ticket;

class TicketExpiredEvent
{
    use SerializesModels;

    public $ticket;

    /**
     * Create a new event instance.
     *
     * @param  Ticket $ticket
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }
}
