<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Ticket;

class TicketStatusChangeEvent
{
    use SerializesModels;

    /**
     * Заказ
     *
     * @var Ticket
     */
    public $ticket;
    /**
     * @var string
     */
    public $adminMessage;

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
