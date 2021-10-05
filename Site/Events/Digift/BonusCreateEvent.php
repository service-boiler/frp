<?php

namespace ServiceBoiler\Prf\Site\Events\Digift;


use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\Mounting;

class BonusCreateEvent
{

	use SerializesModels;
	/**
	 * @var Mounting
	 */
	public $mounting;


	/**
	 * DigiftBonusCreateEvent constructor.
	 *
	 * @param Mounting $mounting
	 */
	public function __construct(Mounting $mounting)
	{

		$this->mounting = $mounting;
	}
}