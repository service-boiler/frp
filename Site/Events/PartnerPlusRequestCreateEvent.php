<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\PartnerPlusRequest;

class PartnerPlusRequestCreateEvent
{
    use SerializesModels;

    public $partnerPlusRequest;

    public function __construct($partnerPlusRequest)
    {
        $this->partnerPlusRequest = $partnerPlusRequest;
    }
}
