<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class WebinarUnauthParticipant extends Model
{
    /**
     * @var string
     */
    protected $table = 'webinar_unauth_participants';

    /**
     * @var bool
     */
    public $timestamps = false;
    
    
    protected $fillable = [
        'webinar_id', 'checkin','visit', 'name', 'email', 'phone', 'company','region','locality','duration'];
        
        public function getPhoneAttribute($value)
    {
        return $value ? preg_replace(config('site.phone.get.pattern'), config('site.phone.get.replacement'), $value) : null;
    }

    /**
     * @param $value
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = $value ? preg_replace(config('site.phone.set.pattern'), config('site.phone.set.replacement'), $value) : null;
    }


}
