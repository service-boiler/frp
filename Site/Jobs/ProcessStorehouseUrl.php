<?php

namespace ServiceBoiler\Prf\Site\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\Storehouse;
use ServiceBoiler\Prf\Site\Exceptions\Storehouse\LoadEmptyDataException;
use ServiceBoiler\Prf\Site\Exceptions\Storehouse\ProductException;
use ServiceBoiler\Prf\Site\Exceptions\Storehouse\UrlNotExistsException;
use ServiceBoiler\Prf\Site\Exceptions\Storehouse\XmlLoadFailedException;
use ServiceBoiler\Prf\Site\Imports\Url\StorehouseXml;

class ProcessStorehouseUrl implements ShouldQueue
{

	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * @var Storehouse
	 */
	private $storehouse;

	/**
	 * Create a new job instance.
	 *
	 * @param Storehouse $storehouse
	 */
	public function __construct(Storehouse $storehouse)
	{
		$this->storehouse = $storehouse;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->storehouse->updateFromUrl(['log' => true]);
	}

}
