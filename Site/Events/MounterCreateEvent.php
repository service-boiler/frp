<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Mounter;

class MounterCreateEvent
{
    use SerializesModels;

    /**
     * Заявка на авторизацию
     *
     * @var Mounter
     */
    public $mounter;

    /**
     * Create a new event instance.
     *
     * @param Mounter $mounter
     */
    public function __construct(Mounter $mounter)
    {
        $this->mounter = $mounter;
    }
}
