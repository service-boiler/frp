<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class UserRelation extends Model
{
  
    protected $fillable = [
        'accepted', 'enabled',
    ];

    protected $casts = [
        'accepted'            => 'integer',
        'enabled'      => 'integer',
    ];
    
    protected $table = 'user_relations';

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
