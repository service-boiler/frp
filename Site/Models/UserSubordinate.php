<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubordinate extends Model
{
  
    protected $fillable = [
    ];

    protected $table = 'user_subordinates';

    public function child()
	{
       return $this->hasOne(
			User::class,
			'id','child_id'
		);
	}
    public function parent()
	{
       return $this->hasOne(
			User::class,
			'id','parent_id'
		);
	}
    
    public function parents()
	{
		return $this->belongsToMany(
			User::class,
			'user_relations',
			'child_id',
            'parent_id'
		);
	}

}
