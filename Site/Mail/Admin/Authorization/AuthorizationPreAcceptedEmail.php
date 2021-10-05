<?php

namespace ServiceBoiler\Prf\Site\Mail\Admin\Authorization;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Authorization;

class AuthorizationPreAcceptedEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var Authorization
     */
    public $authorization;

    /**
     * Create a new message instance.
     * @param Authorization $authorization
     */
    public function __construct(Authorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans('site::authorization.email.pre_accepted.title'))
            ->view('site::email.admin.authorization.pre_accepted');
    }
}
