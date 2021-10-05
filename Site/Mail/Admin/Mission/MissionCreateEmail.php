<?php

namespace ServiceBoiler\Prf\Site\Mail\Admin\Mission;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Mission;

class MissionCreateEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var Mission
     */
    public $mission;

    /**
     * Create a new message instance.
     * @param Mission $mission
     */
    public function __construct(Mission $mission)
    {
        $this->mission = $mission;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Новая '.trans('site::admin.mission.mission'))
            ->view('site::email.admin.mission.create');
    }
}
