<?php

namespace ServiceBoiler\Prf\Site\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\DistributorSale;
use ServiceBoiler\Prf\Site\Exceptions\DistributorSale\LoadEmptyDataException;
use ServiceBoiler\Prf\Site\Exceptions\DistributorSale\ProductException;
use ServiceBoiler\Prf\Site\Exceptions\DistributorSale\UrlNotExistsException;
use ServiceBoiler\Prf\Site\Exceptions\DistributorSale\XmlLoadFailedException;
use ServiceBoiler\Prf\Site\Imports\Url\DistributorSaleXml;

class ProcessDistributorSaleUrl implements ShouldQueue
{

	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * @var DistributorSale
	 */
	private $distributorSale;

	/**
	 * Create a new job instance.
	 *
	 * @param DistributorSale $distributorSale
	 */
	public function __construct(DistributorSale $distributorSale)
	{
		$this->distributorSale = $distributorSale;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{   
		$this->distributorSale->updateFromUrl(['log' => true, 'user'=>$this->distributorSale->user]);
	}

}
