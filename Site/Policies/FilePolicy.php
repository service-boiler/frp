<?php

namespace ServiceBoiler\Prf\Site\Policies;


use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Models\User;

class FilePolicy
{

    public function before(User $user, $ability)
    {
        if ($user->admin == 1) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the file.
     *
     * @param User $user
     * @param File $file
     * @return bool
     */
    public function view(User $user, File $file)
    {
        
        return $file->user_id == $user->getKey() || $file->fileable->user_id == $user->getKey() || $file->fileable_type == 'tenders' 
                || ($user->hasPermission('admin_repairs') && $file->fileable_type == 'repairs');
    }

    /**
     * Determine whether the user can create files.
     *
     * @param  User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->id > 0;
    }

    /**
     * Determine whether the user can update the file.
     *
     * @param  User $user
     * @param  File $file
     * @return bool
     */
    public function update(User $user, File $file)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the file.
     *
     * @param  User $user
     * @param  File $file
     * @return bool
     */
    public function delete(User $user, File $file)
    {
        return true;
    }


}
