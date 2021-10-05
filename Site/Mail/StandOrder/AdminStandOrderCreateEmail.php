<?php

namespace ServiceBoiler\Prf\Site\Mail\StandOrder;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\StandOrder;

class AdminStandOrderCreateEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var StandOrder
     */
    public $standOrder;

    /**
     * Create a new message instance.
     * @param StandOrder $standOrder
     */
    public function __construct(StandOrder $standOrder)
    {
        $this->standOrder = $standOrder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans('site::stand_order.email_create_title'))
            ->view('site::email.stand_order.admin_create');
    }
}
