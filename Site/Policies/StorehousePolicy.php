<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Storehouse;
use ServiceBoiler\Prf\Site\Models\User;

class StorehousePolicy
{

    /**
     * Determine whether the user can create posts.
     *
     * @param  User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->admin == 0 || $user->storehouses()->doesntExist();
    }

    /**
     * Determine whether the user can view the post.
     *
     * @param User $user
     * @param Storehouse $storehouse
     * @return bool
     */
    public function view(User $user, Storehouse $storehouse)
    {
        //return $user->getKey() == $storehouse->getAttribute('user_id');
        return (in_array($storehouse->user->region_id,$user->notifiRegions->pluck('id')->toArray()) && $user->hasRole('ferroli_user')) 
                || $user->getKey() == $storehouse->getAttribute('user_id') || $user->admin == 1 || $user->hasRole('supervisor') || $user->hasRole('service_super');
    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  User $user
     * @param  Storehouse $storehouse
     * @return bool
     */
    public function edit(User $user, Storehouse $storehouse)
    {
        return $user->id == $storehouse->getAttribute('user_id');
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  User $user
     * @param  Storehouse $storehouse
     * @return bool
     */
    public function delete(User $user, Storehouse $storehouse)
    {
        return $user->id == $storehouse->getAttribute('user_id');
    }

	/**
	 * Determine whether the user can view the post.
	 *
	 * @param User $user
	 * @param Storehouse $storehouse
	 * @return bool
	 */
	public function download(User $user, Storehouse $storehouse)
	{
		return $storehouse->products()->exists();
	}


}
