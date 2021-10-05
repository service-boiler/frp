<?php

namespace ServiceBoiler\Prf\Site\Mail\StandOrder;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\StandOrder;

class UserStandOrderStatusChangeEmail extends Mailable implements ShouldQueue
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
            ->subject(trans('site::stand_order.email_status_change_title'))
            ->view('site::email.stand_order.user_status_change');
    }
}
