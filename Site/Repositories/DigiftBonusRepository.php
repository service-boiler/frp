<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\DigiftBonus\DigiftBonusDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\DigiftBonus\DigiftBonusDateToFilter;
use ServiceBoiler\Prf\Site\Filters\DigiftBonus\DigiftBonusUserRegionSelectFilter;
use ServiceBoiler\Prf\Site\Filters\DigiftBonus\DigiftBonusUserSearchFilter;
use ServiceBoiler\Prf\Site\Models\DigiftBonus;

class DigiftBonusRepository extends Repository
{

	/**
	 * Specify Model class name
	 *
	 * @return mixed
	 */
	public function model()
	{
		return DigiftBonus::class;
	}

	/**
	 * @return array
	 */
	public function track(): array
	{
		return [
			DigiftBonusUserRegionSelectFilter::class,
			DigiftBonusDateFromFilter::class,
			DigiftBonusDateToFilter::class,
			DigiftBonusUserSearchFilter::class,

		];
	}
}