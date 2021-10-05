<?php

namespace ServiceBoiler\Prf\Site\Mail\DistributorSale;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Order;
use ServiceBoiler\Prf\Site\Models\DistributorSaleLog;

class UserDistributorSaleLogCreateEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

	/**
	 * @var DistributorSaleLog
	 */
	public $distributorSaleLog;

	/**
	 * Create a new message instance.
	 *
	 * @param DistributorSaleLog $distributorSaleLog
	 */
    public function __construct(DistributorSaleLog $distributorSaleLog)
    {
	    $this->distributorSaleLog = $distributorSaleLog;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans('site::distributor_sales.email.error_title'))
            ->view('site::email.distributor_sale_log.user.create');
    }
}
