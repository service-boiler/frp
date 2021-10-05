<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Authorization;

class AuthorizationPreAcceptedEvent
{
    use SerializesModels;

    /**
     * Заказ
     *
     * @var Authorization
     */
    public $authorization;
    /**
     * @var string
     */

    /**
     * Create a new event instance.
     *
     * @param  Authorization $authorization
     */
    public function __construct($authorization)
    {
        $this->authorization = $authorization;
    }
}
