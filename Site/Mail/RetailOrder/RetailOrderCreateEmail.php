<?php

namespace ServiceBoiler\Prf\Site\Mail\RetailOrder;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\RetailOrder;

class RetailOrderCreateEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var Order
     */
    public $retailorder;
	public $content;
	public $title;
	public $address;

    /**
     * Create a new message instance.
     * @param Order $order
     */
    public function __construct($title, $content, $address)
    {	
        $this->title = $title;
        $this->content = $content;
        $this->address = $address;
		
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject($this->title)
            ->view('site::retail_order.mail_for_dealer');
    }
}
