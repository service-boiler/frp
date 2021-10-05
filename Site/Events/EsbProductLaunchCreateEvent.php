<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\EsbProductLaunch;

class EsbProductLaunchCreateEvent
{
    use SerializesModels;

   
    public $esbProductLaunch;

   
    public function __construct(EsbProductLaunch $esbProductLaunch)
    {
        $this->esbProductLaunch = $esbProductLaunch;
    }
}
