<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Promocode extends Model
{

    /**
     * @var string
     */
    protected $table = 'promocodes';
    
    protected $fillable = [
        'promoable_id', 'name', 'bonuses', 'comment','expiry','token','short_token'
    ];
    
    protected $casts = [

		'promoable_id' => 'integer',
		'bonuses' => 'integer',
		'expiry' => 'date:Y-m-d',
		'name' => 'string',
		'comment' => 'string',
		'token' => 'string',
		'short_token' => 'string',
	];
    
    protected $dates = [
		'expiry',
	];
    
    public function users()
	{
		return $this->belongsToMany(
			User::class,
			'promocode_users',
			'promocode_id',
			'user_id'
		);
	}
    
    public function setExpiryAttribute($value)
	{
		$this->attributes['expiry'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
	}
   
}
