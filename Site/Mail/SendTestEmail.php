<?php

namespace ServiceBoiler\Prf\Site\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Engineer;

class SendTestEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
   
    public $linktest;
    public $engeneer;
    

    public function __construct($linktest, Engineer $engeneer)
    {
        $this->linktest = $linktest;
        $this->engeneer = $engeneer;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        return $this
            ->subject(trans('site::engineer.email_link_title'))
            ->view('site::email.tests.create');
    }
}
