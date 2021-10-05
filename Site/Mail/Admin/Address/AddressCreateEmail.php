<?php

namespace ServiceBoiler\Prf\Site\Mail\Admin\Address;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Address;

class AddressCreateEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * @var Address
     */
    public $address;

    /**
     * Create a new message instance.
     * @param Address $address
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
            ->subject('Добавлен адрес '.trans('site::address.address'))
            ->view('site::email.admin.address.create');
    }
}
