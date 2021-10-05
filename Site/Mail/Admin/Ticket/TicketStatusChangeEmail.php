<?php

namespace ServiceBoiler\Prf\Site\Mail\Admin\Ticket;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Ticket;

class TicketStatusChangeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var Ticket
     */
    public $ticket;
    /**
     * @var string
     */
    public $adminMessage;

    /**
     * Create a new message instance.
     * @param Ticket $ticket
     * @param null $adminMessage
     */
    public function __construct(Ticket $ticket, $adminMessage = null)
    {
        $this->ticket = $ticket;
        $this->adminMessage = $adminMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Тикет. Смена статуса ' .$this->ticket->status->name)
            ->view('site::email.admin.ticket.status')->with('adminMessage', $this->adminMessage);
    }
}
