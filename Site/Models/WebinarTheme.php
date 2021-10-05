<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class WebinarTheme extends Model
{

    /**
     * @var string
     */
    protected $table = 'webinar_themes';
    
    protected $fillable = [
        'active', 'name', 'promocode_id', 'comment',
    ];

    
    public function webinars()
	{
		return $this->hasMany(Webinar::class);
	}
    
    public function promocode()
    {
        return $this->hasOne(Promocode::class,'id','promocode_id');
    }
    
}
