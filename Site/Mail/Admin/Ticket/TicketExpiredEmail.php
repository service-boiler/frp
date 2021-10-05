<?php

namespace ServiceBoiler\Prf\Site\Mail\Admin\Ticket;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Ticket;

class TicketExpiredEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
  
    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

     public function build()
    {
        return $this->subject('Тикет №' .$this->ticket->id .'. Не решен!')->view('site::email.admin.ticket.expired');
   }
}
