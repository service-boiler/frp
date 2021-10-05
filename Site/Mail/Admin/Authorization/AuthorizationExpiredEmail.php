<?php

namespace ServiceBoiler\Prf\Site\Mail\Admin\Authorization;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Authorization;

class AuthorizationExpiredEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var Authorization
     */
    public $authorization;

    /**
     * Create a new message instance.
     * @param Authorization $authorization
     * @param null $adminMessage
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
        return $this->subject('Заявка на авторизацию №' .$this->authorization->id .'. не обрабоатна более недели')->view('site::email.admin.authorization.expired');
   }
}
