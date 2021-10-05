<?php

namespace ServiceBoiler\Prf\Site\Mail\UserFlRoleRequest;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\UserFlRoleRequest;

class AdminUserFlRoleRequestCreateEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var UserFlRoleRequest
     */
    public $userFlRoleRequest;

    /**
     * Create a new message instance.
     * @param UserFlRoleRequest $userFlRoleRequest
     */
    public function __construct(UserFlRoleRequest $userFlRoleRequest)
    {
        $this->userFlRoleRequest = $userFlRoleRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans('site::user.role_request_email.title'))
            ->view('site::email.role_request.admin.create');
    }
}
