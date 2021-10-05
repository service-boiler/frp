<?php

namespace ServiceBoiler\Prf\Site\Mail\EsbRequest;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\EsbUserRequest;

class AdminEsbRequestCreateEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var EsbUserRequest
     */
    public $esbRequest;

    /**
     * Create a new message instance.
     * @param EsbRequest $esbRequest
     */
    public function __construct(EsbUserRequest $esbRequest)
    {
        $this->esbRequest = $esbRequest;
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
            ->view('site::esb_request.email_create_admin');
    }
}
