<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\DigiftUser;

class DigiftUserRepository extends Repository
{

	/**
	 * Specify Model class name
	 *
	 * @return mixed
	 */
	public function model()
	{
		return DigiftUser::class;
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