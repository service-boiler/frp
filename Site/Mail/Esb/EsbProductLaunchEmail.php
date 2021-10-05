<?php

namespace ServiceBoiler\Prf\Site\Mail\Esb;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\EsbProductLaunch;

class EsbProductLaunchEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $esbProductLaunch;
    public $view;
    public $subject;


    public function __construct(EsbProductLaunch $esbProductLaunch, $view, $subject = 'Ferroli')
    {
        $this->esbProductLaunch = $esbProductLaunch;
        $this->view = $view;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->subject.' ' .$this->esbProductLaunch->created_at->format('d.m.Y').'/' .$this->esbProductLaunch->id)
            ->view($this->view);
    }
}
