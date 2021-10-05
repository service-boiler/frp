<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\EsbUserVisit;

class EsbVisitStatusEvent
{
    use SerializesModels;

   
    public $esbUserVisit;

    
    public function __construct(EsbUserVisit $esbUserVisit)
    {
        $this->esbUserVisit = $esbUserVisit;
    }
}
