<?php

namespace ServiceBoiler\Prf\Site\Mail\Esb;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\User;

class EsbServiceChangeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $esbUser;
    public $view;


    public function __construct(User $esbUser, $view)
    {
        $this->esbUser = $esbUser;
        $this->view = $view;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this
            ->subject(trans('site::user.esb_user.service_change_subject'))
            ->view($this->view);
    }
}
