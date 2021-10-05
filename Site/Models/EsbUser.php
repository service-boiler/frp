<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class EsbUser extends Model
{
  
    protected $fillable = [
        'accepted', 'enabled',
    ];

    protected $casts = [
        'accepted'            => 'integer',
        'enabled'      => 'integer',
    ];
    
    protected $table = 'esb_users';

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
    
    
    public function getUserFiltredAttribute () {
        if($this->esbUser->esbRequests()->where('status_id','!=',7)->where('recipient_id',auth()->user()->id)->exists()
                     || $this->esbUser->esbServices()->wherePivot('enabled',1)->get()->contains(auth()->user()->id) ){
            return $this->esbUser->name;
        } else {
            return $this->esbUser->first_name .' ' .mb_substr($this->esbUser->last_name,0,1);
        }
    
    }
    

}
