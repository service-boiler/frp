<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class EsbUserServices extends Model
{
  
    protected $fillable = [
        'accepted', 'enabled',
    ];

    protected $casts = [
        'accepted'            => 'integer',
        'enabled'      => 'integer',
    ];
    
    protected $table = 'esb_user_services';

    public function esbUser()
	{
       return $this->hasOne(
			User::class,
			'id','esb_user_id'
		);
	}
    public function service()
	{
       return $this->hasOne(
			User::class,
			'id','service_id'
		);
	}
    
    

}
