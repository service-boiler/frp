<?php

namespace ServiceBoiler\Prf\Site\Mail\Admin\Mission;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Mission;

class MissionStatusChangeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var Mission
     */
    public $mission;
    /**
     * @var string
     */
    public $adminMessage;

    /**
     * Create a new message instance.
     * @param Mission $mission
     * @param null $adminMessage
     */
    public function __construct(Mission $mission, $adminMessage = null)
    {
        $this->mission = $mission;
        $this->adminMessage = $adminMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Командировка. Смена статуса ' .$this->mission->status->name)
            ->view('site::email.admin.mission.status')->with('adminMessage', $this->adminMessage);
    }
}
