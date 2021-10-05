<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\UserRelation;

class UserRelationCreateEvent
{
    use SerializesModels;

    public $userRelation;

    public function __construct(UserRelation $userRelation)
    {
        $this->userRelation = $userRelation;
    }
}
