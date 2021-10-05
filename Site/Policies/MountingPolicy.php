<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Mounting;
use ServiceBoiler\Prf\Site\Models\User;

class MountingPolicy
{

	/**
	 * Determine whether the user can create posts.
	 *
	 * @param  User $user
	 *
	 * @return bool
	 */
	public function create(User $user)
	{
		return $user->id > 0;
	}

	public function pdf(User $user, Mounting $mounting)
	{
		return $mounting->getAttribute('status_id') == 2 && $this->view($user, $mounting);
	}

	/**
	 * Determine whether the user can view the post.
	 *
	 * @param User $user
	 * @param Mounting $mounting
	 *
	 * @return bool
	 */
	public function view(User $user, Mounting $mounting)
	{
		//return $user->admin == 1 || $user->id == $mounting->getAttribute('user_id');
        return (in_array($mounting->user->region_id,$user->notifiRegions->pluck('id')->toArray()) && $user->hasRole('ferroli_user')) || $user->id == $mounting->getAttribute('user_id') || $user->admin == 1 || $user->hasRole('supervisor');
	}

	/**
	 * Determine whether the user can update the mounting.
	 *
	 * @param  User $user
	 * @param  Mounting $mounting
	 *
	 * @return bool
	 */
	public function update(User $user, Mounting $mounting)
	{
		return $user->id == $mounting->user_id || $user->admin == 1 || $user->hasRole('supervisor');
	}

	/**
	 * Determine whether the user can create Digift Bonus.
	 *
	 * @param  User $user
	 * @param  Mounting $mounting
	 *
	 * @return bool
	 */
	public function digiftBonus(User $user, Mounting $mounting)
	{
		return $mounting->digiftBonus()->doesntExist()
			&& $mounting->getAttribute('status_id') == 2;
	}

	/**
	 * Determine whether the user can delete the mounting.
	 *
	 * @param  User $user
	 * @param  Mounting $mounting
	 *
	 * @return bool
	 */
	public function delete(User $user, Mounting $mounting)
	{
		return false;
	}


}
