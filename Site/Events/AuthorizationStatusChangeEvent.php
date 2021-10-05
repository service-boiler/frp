<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Tender;

class AuthorizationStatusChangeEvent
{
    use SerializesModels;

    /**
     * Заказ
     *
     * @var Tender
     */
    public $authorization;
    /**
     * @var string
     */

    /**
     * Create a new event instance.
     *
     * @param  Tender $authorization
     */
    public function __construct($authorization)
    {
        $this->authorization = $authorization;
    }
}
