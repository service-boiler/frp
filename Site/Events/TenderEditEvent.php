<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Tender;

class TenderEditEvent
{
    use SerializesModels;

    public $tender;

    public function __construct($tender)
    {
        $this->tender = $tender;
    }
}
