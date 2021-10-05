<?php

namespace ServiceBoiler\Prf\Site\Mail\Esb;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\EsbUserRequest;

class EsbRequestEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var EsbUserRequest
     */
    public $esbRequest;
    public $view;

    /**
     * Create a new message instance.
     * @param EsbRequest $esbRequest
     */
    public function __construct(EsbUserRequest $esbRequest, $view)
    {
        $this->esbRequest = $esbRequest;
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
            ->subject(trans('site::user.esb_request.email_create_subject') .' ' .$this->esbRequest->created_at->format('d.m.Y').'/' .$this->esbRequest->id)
            ->view($this->view);
    }
}
