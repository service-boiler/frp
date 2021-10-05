<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\StandOrderItem;
use ServiceBoiler\Prf\Site\Models\User;

class StandOrderItemPolicy
{

	/**
	 * Determine whether the user can delete the post.
	 *
	 * @param  User $user
	 * @param  OrderItem $orderItem
	 *
	 * @return bool
	 */
	public function delete(User $user, StandOrderItem $standOrderItem)
	{ 
		return in_array($standOrderItem->standOrder->status_id, array(1,7));
	}

	public function edit(User $user, StandOrderItem $standOrderItem)
	{
		return  ($user->admin == 1 || $user->hasRole('supervisor') || ($user->hasRole('ferroli_user'))) && in_array($standOrderItem->standOrder->status_id, array(1,7));
	}


}
