<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Member;

class MemberCreateEvent
{
    use SerializesModels;

    /**
     * Заявка
     *
     * @var Member
     */
    public $member;

    /**
     * Create a new event instance.
     *
     * @param  Member $member
     */
    public function __construct($member)
    {
        $this->member = $member;
    }
}
