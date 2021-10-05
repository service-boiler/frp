<?php

namespace ServiceBoiler\Prf\Site\Mail\Admin\Tender;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Tender;

class TenderCurrencyExpiredEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var Tender
     */
    public $tenders;

    /**
     * Create a new message instance.
     * @param Tender $tender
     * @param null $adminMessage
     */
    public function __construct($tenders)
    {
        $this->tenders = $tenders;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Тендеры. Курс валюты вышел из коридора')->view('site::email.admin.tender.currency_expired');
   }
}
