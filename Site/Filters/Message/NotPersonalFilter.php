<?php

namespace ServiceBoiler\Prf\Site\Filters\Message;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class NotPersonalFilter extends Filter
{

	function apply($builder, RepositoryInterface $repository)
	{
		return $builder->where('personal', 0);
	}
}