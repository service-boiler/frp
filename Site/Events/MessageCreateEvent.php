<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Message;

class MessageCreateEvent
{
    use SerializesModels;

    /**
     * Заявка на авторизацию
     *
     * @var Message
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @param  Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }
}
