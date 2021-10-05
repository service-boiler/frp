<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Concerns\AttachParticipants;
use ServiceBoiler\Prf\Site\Contracts\SingleImageable;
use ServiceBoiler\Prf\Site\Services\Zoom;

class Webinar extends Model implements SingleImageable
{
    use AttachParticipants;
    /**
     * @var string
     */
    protected $table = 'webinars';
    
    protected $fillable = [
        'name', 'theme_id', 'type_id', 'datetime','annotation','description','image_id','promocode_id','image_id','enabled',
        'id_service','service_name','link_service','zoom_id','duration'
    ];

    
    protected $casts = [
		'theme_id' => 'integer',
		'type_id' => 'integer',
		'date' => 'date:Y-m-d HH:MM',
		'link_service' => 'string',
		'service_name' => 'string',
		'description' => 'string',
	];
    
    protected $dates = [
		'datetime',
	];
    
     public function setDatetimeAttribute($value)
	{      
		$this->attributes['datetime'] = $value ? Carbon::createFromFormat('d.m.Y H:i', $value) : null;
        
	}
  
    public function type()
    {
        return $this->belongsTo(WebinarType::class);
    }
    
    public function promocode()
    {
        return $this->belongsTo(Promocode::class);
    }
    
    public function theme()
    {
        return $this->belongsTo(WebinarTheme::class);
    }
    
    public function users()
	{
		return $this->belongsToMany(
			User::class,
			'webinar_users',
			'webinar_id',
			'user_id'
		);
	} 
    
    public function unauthParticipants()
	{
		return $this->hasMany(
			WebinarUnauthParticipant::class
			
		);
	} 
    
    public function usersVisits()
	{   return $this->belongsToMany(
			User::class,
			'webinar_users',
			'webinar_id',
			'user_id'
		)->whereNotNull('webinar_users.visit');
	}
    
    public function image()
    {
        return $this->belongsTo(Image::class)->withDefault([
            'storage' => 'webinars',
            'path'    => 'noimage.png',
        ]);
    }
    
     function imageStorage()
    {
        return 'webinars';
    }
    
    public function registerUserOnWebinar(User $user) {
        
        if (!$this->participants->contains($user->id)) {
            $this->AttachParticipant($user);
                    
        }
        
        if($this->service_name=='zoom') {
            
            $response = (new Zoom())->getWebinarRegistrants($this,'denied')->request();
                $body = json_decode($response->getBody(), true);
                $denied=collect($body['registrants']);
                if($denied->contains('email',$user->email)) {
                    return 'you_denied';
                }
                
            $response = (new Zoom())->addWebinarUserRegistrant($this, $user)->request();

                if (in_array($response->getStatusCode(),['200','201'])) {
                    $body = json_decode($response->getBody(), true);
                    if (!empty($body['registrant_id'])) {
                        $this->participants()->updateExistingPivot($user, [
                                                                                'zoom_registrant_id' => $body['registrant_id'],
                                                                                'zoom_reigistrant_join_url' => $body['join_url']
                                                                                ]);
                        
                    } else {
                        throw new ZoomException($body['message']);
                    }
                }
        
        }
     
        return 'true';
    
    }
}
