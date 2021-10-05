<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\DigiftExpense;

class DigiftExpenseRepository extends Repository
{

	/**
	 * Specify Model class name
	 *
	 * @return mixed
	 */
	public function model()
	{
		return DigiftExpense::class;
	}

	/**
	 * @return array
	 */
	public function track(): array
	{
		return [

		];
	}
}