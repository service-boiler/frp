<?php

namespace ServiceBoiler\Prf\Site\Mail\UserRelation;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\UserRelation;

class UserUserRelationCreateEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var UserRelation
     */
    public $userRelation;

    /**
     * Create a new message instance.
     * @param UserRelation $userRelation
     */
    public function __construct(UserRelation $userRelation)
    {
        $this->userRelation = $userRelation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans('site::user.relation_email.title'))
            ->view('site::email.user_relation.user.create');
    }
}
