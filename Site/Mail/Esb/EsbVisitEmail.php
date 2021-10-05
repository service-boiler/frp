<?php

namespace ServiceBoiler\Prf\Site\Mail\Esb;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\EsbUserVisit;

class EsbVisitEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var EsbUserVisit
     */
    public $esbVisit;
    public $subject;
    public $view;

    /**
     * Create a new message instance.
     * @param EsbVisit $esbVisit
     */
    public function __construct(EsbUserVisit $esbVisit, $view, $subject='Ferroli')
    {
        $this->esbVisit = $esbVisit;
        $this->subject = $subject;
        $this->view = $view;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this
            ->subject($this->subject .' ' .$this->esbVisit->created_at->format('d.m.Y').'/' .$this->esbVisit->id)
            ->view($this->view);
    }
}
