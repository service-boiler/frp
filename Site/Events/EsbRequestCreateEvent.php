<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\EsbUserRequest;

class EsbRequestCreateEvent
{
    use SerializesModels;

   
    public $esbRequest;

    /**
     * Create a new event instance.
     *
     * @param EsbRequest $esbRequest
     */
    public function __construct(EsbUserRequest $esbRequest)
    {
        $this->esbRequest = $esbRequest;
    }
}
