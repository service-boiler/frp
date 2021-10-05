<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Tender;

class TenderCreateEvent
{
    use SerializesModels;

    /**
     *
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $tender;

    /**
     * Create a new event instance.
     *
     * @param  Tender $tender
     */
    public function __construct($tender)
    {
        $this->tender = $tender;
    }
}
