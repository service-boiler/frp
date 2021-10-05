<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Authorization;

class AuthorizationChangeEvent
{
    use SerializesModels;

    /**
     * Заявка на авторизацию
     *
     * @var Authorization
     */
    public $authorization;

    /**
     * Create a new event instance.
     *
     * @param  Authorization $authorization
     */
    public function __construct(Authorization $authorization)
    {
        $this->authorization = $authorization;
		
    }
}
