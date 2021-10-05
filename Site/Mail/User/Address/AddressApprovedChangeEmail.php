<?php

namespace ServiceBoiler\Prf\Site\Mail\User\Address;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Address;

class AddressApprovedChangeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var Address
     */
    public $address;

    /**
     * Create a new message instance.
     * @param Address $address
     * @param null $adminMessage
     */
    public function __construct(Address $address)
    {
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
            ->subject(trans('site::address.email_approved_title'))
            ->view('site::email.user.address.approved');
    }
}
