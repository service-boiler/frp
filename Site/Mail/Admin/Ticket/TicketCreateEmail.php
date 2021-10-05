<?php

namespace ServiceBoiler\Prf\Site\Mail\Admin\Ticket;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Ticket;

class TicketCreateEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var Ticket
     */
    public $ticket;

    /**
     * Create a new message instance.
     * @param Ticket $ticket
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Новый '.trans('site::ticket.ticket'))
            ->view('site::email.admin.ticket.create');
    }
}
