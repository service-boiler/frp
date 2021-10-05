<?php

namespace ServiceBoiler\Prf\Site\Filters\DigiftBonus;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class DigiftBonusCheckBalanceFilter extends Filter
{

	function apply($builder, RepositoryInterface $repository)
	{
		return $builder
			->whereHas('digiftUser.user', function ($user) {
				$user->where('active', 1);
			})
			->where('digift_bonuses.sended', 1)
			->where('digift_bonuses.blocked', 0);
	}

}