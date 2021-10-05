<?php

namespace ServiceBoiler\Prf\Site\Mail\User\Tender;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Tender;

class TenderStatusEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var Tender
     */
    public $tender;
    /**
     * @var string
     */
    public $adminMessage;

    /**
     * Create a new message instance.
     * @param Tender $tender
     * @param null $adminMessage
     */
    public function __construct(Tender $tender, $adminMessage = null)
    {
        $this->tender = $tender;
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
            ->subject('Уведомление от service.ferroli.ru')
            ->view('site::email.user.tender.status')->with('adminMessage', $this->adminMessage);
    }
}
