<?php

namespace ServiceBoiler\Prf\Site\Events\Digift;


use Illuminate\Queue\SerializesModels;
use ServiceBoiler\Prf\Site\Models\DigiftUser;

class UserBalanceMismatchEvent
{

	use SerializesModels;
	/**
	 * @var DigiftUser
	 */
	public $digiftUser;
	/**
	 * @var array
	 */
	public $balance;


	/**
	 * Create a new event instance.
	 *
	 * @param DigiftUser $digiftUser
	 * @param array $balance
	 */
	public function __construct(DigiftUser $digiftUser, array $balance)
	{
		$this->digiftUser = $digiftUser;
		$this->balance = $balance;
	}
}