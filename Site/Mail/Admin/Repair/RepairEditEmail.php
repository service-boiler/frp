<?php

namespace ServiceBoiler\Prf\Site\Mail\Admin\Repair;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Repair;

class RepairEditEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var Repair
     */
    public $repair;

    /**
     * Create a new message instance.
     * @param Repair $repair
     */
    public function __construct(Repair $repair)
    {
        $this->repair = $repair;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Исправлен '.trans('site::repair.repair'))
            ->view('site::email.admin.repair.edit');
    }
}
