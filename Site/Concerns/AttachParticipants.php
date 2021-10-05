<?php

namespace ServiceBoiler\Prf\Site\Concerns;

use ServiceBoiler\Prf\Site\Models\Webinar;
use ServiceBoiler\Prf\Site\Models\User;

trait AttachParticipants
{
    /**
     * Attach multiple participants to a webinar
     *
     * @param mixed $participants
     */
    public function attachParticipants(array $participants)
    {
        foreach ($participants as $participant) {
            $this->attachParticipant($participant);
        }
    }


    /**
     *
     * @param mixed $participant
     */
    public function attachParticipant($participant, $zoom_id=null)
    {
        
        if (is_object($participant)) {
            $participant = $participant->getKey();
        }
        if (is_array($participant)) {
            $participant = $participant['id'];
        }
        
        $this->participants()->attach($participant);
        
        
    }

    /**
     * Detach multiple participants from a webinar
     *
     * @param mixed $participants
     */
    public function detachParticipants(array $participants)
    {
        if (!$participants) {
            $participants = $this->participants()->get();
        }
        foreach ($participants as $participant) {
            $this->detachParticipant($participant);
        }
    }

    /**
     * @param $participants
     */
    public function syncParticipants($participants)
    {
        if (!is_array($participants)) {
            $participants = [$participants];
        }
        $this->participants()->sync($participants);
    }

    /**
    
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function participants()
    {
        return $this->belongsToMany(
            User::class,
            'webinar_users',
            'webinar_id',
            'user_id')->withPivot('checkin','visit','duration','zoom_registrant_id','zoom_reigistrant_join_url','comment','leave_time','join_time');
    }

    /**
     * Удалить аналог
     *
     * @param mixed $participant
     */
    public function detachParticipant($participant)
    {
        if (is_object($participant)) {
            $participant = $participant->getKey();
        }
        if (is_array($participant)) {
            $participant = $participant['id'];
        }
        $this->participants()->detach($participant);
    }
   

}