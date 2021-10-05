<?php

namespace ServiceBoiler\Prf\Site\Filters\DigiftBonus;

use ServiceBoiler\Repo\Filters\BootstrapInput;
use ServiceBoiler\Repo\Filters\SearchFilter as BaseFilter;

class DigiftBonusUserSearchFilter extends BaseFilter
{

	use BootstrapInput;

	protected $render = true;
	protected $search = 'search';

	protected function columns()
	{
		return [
			'users.name',
		];
	}

	public function label()
	{
		return trans('site::digift_bonus.placeholder.search');
	}

}