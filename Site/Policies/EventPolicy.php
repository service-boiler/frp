<?php

namespace ServiceBoiler\Prf\Site\Policies;

use ServiceBoiler\Prf\Site\Models\Event;
use ServiceBoiler\Prf\Site\Models\User;

class EventPolicy
{

    /**
     * Determine whether the user can delete the event.
     *
     * @param  User $user
     * @param  Event $event
     * @return bool
     */
    public function delete(User $user, Event $event)
    {
        return $user->getAttribute('admin') == 1 && in_array($event->getAttribute('status_id'), [1, 4, 5]) && $event->members()->count() == 0;
    }
    
    public function edit(User $user, Event $event)
    {
        return $user->getAttribute('admin') == 1 || $user->hasPermission('admin_events_edit');
    }

    public function view(User $user, Event $event)
    {
        $check_field = config('site.check_field');

        return $event->getAttribute($check_field) == 1;
    }


}
