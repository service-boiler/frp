<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\User;

class UserScheduleEvent
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  User $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
