<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserPrereg extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_preregs';
    
   protected static function boot()
    {
        static::creating(function (UserPrereg $model) {
            $model->setAttribute('guid', Str::uuid()->toString());
        });

    }
    
    protected $fillable = [
        'lastname', 'firstname', 'middlename', 'phone', 'email', 'region_id', 'locality', 'parent_user_id', 'role_id', 'ferroli_manager_id', 'user_id', 'visits','parent_name','invited','guid'
    ];

    public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
    public function role()
	{
		return $this->belongsTo(Role::class, 'role_id');
	}
    
    public function parentUser()
	{
		return $this->belongsTo(User::class, 'parent_user_id');
	}
    
    public function region()
	{
		return $this->belongsTo(Region::class, 'region_id');
	}

 
}
