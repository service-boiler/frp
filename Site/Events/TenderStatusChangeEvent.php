<?php

namespace ServiceBoiler\Prf\Site\Events;

use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Tender;

class TenderStatusChangeEvent
{
    use SerializesModels;

    /**
     * Заказ
     *
     * @var Tender
     */
    public $tender;
    /**
     * @var string
     */
    public $adminMessage;

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
