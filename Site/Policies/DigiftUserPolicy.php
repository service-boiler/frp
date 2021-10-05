<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\DigiftUser;
use ServiceBoiler\Prf\Site\Models\User;

class DigiftUserPolicy
{

	public function fullUrlToRedirect(User $user, DigiftUser $digiftUser)
	{
		return $user->getAttribute('active') == 1 && $digiftUser->balance > 0;
	}

	public function rollbackBalanceChange(User $user, DigiftUser $digiftUser)
	{
		return $user->getAttribute('active') == 1 && $digiftUser->exists() && $digiftUser->accruedDigiftBonuses()->exists();
	}

	public function refreshToken(User $user, DigiftUser $digiftUser)
	{
		return $user->getAttribute('active') == 1 && $digiftUser->exists();
	}

}
