<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Contact;
use ServiceBoiler\Prf\Site\Models\User;

class ContactPolicy
{

    public function index(User $user)
    {
        return $user->hasPermission('contacts');
    }

    /**
     * Determine whether the user can view the contact.
     *
     * @param User $user
     * @param Contact $contact
     * @return bool
     */
    public function view(User $user, Contact $contact)
    {
        return $user->hasPermission('contacts') && $this->belongsUser($user, $contact);
    }

    /**
     * Determine whether the user can update the address.
     *
     * @param  User $user
     * @param  Contact $contact
     * @return bool
     */
    public function edit(User $user, Contact $contact)
    {
        return $user->hasPermission('contacts') && $this->belongsUser($user, $contact);
    }

    private function belongsUser(User $user, Contact $contact)
    {
        return $user->id == $contact->getAttribute('user_id');
    }

    public function phone(User $user, Contact $contact)
    {
        return $this->belongsUser($user, $contact);
    }

    /**
     * Determine whether the user can create contacts.
     *
     * @param  User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermission('contacts') && ($user->id > 0);
    }

    /**
     * Determine whether the user can update the contact.
     *
     * @param  User $user
     * @param  Contact $contact
     * @return bool
     */
    public function update(User $user, Contact $contact)
    {
        return $user->hasPermission('contacts') && $this->belongsUser($user, $contact);
    }

    /**
     * Determine whether the user can delete the contact.
     *
     * @param  User $user
     * @param  Contact $contact
     * @return bool
     */
    public function delete(User $user, Contact $contact)
    {
        return $user->hasPermission('contacts') && $this->belongsUser($user, $contact) && $contact->type->getAttribute('enabled') == 1;
    }

}
