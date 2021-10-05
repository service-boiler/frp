<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Mounting;

class MountingCreateEvent
{
    use SerializesModels;

    /**
     * Заявка на авторизацию
     *
     * @var Mounting
     */
    public $mounting;

    /**
     * Create a new event instance.
     *
     * @param  Mounting $mounting
     */
    public function __construct(Mounting $mounting)
    {
        $this->mounting = $mounting;
    }
}
