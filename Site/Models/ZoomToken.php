<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class ZoomToken extends Model
{

	/**
	 * @var array id
	 */
	protected $fillable = [
		'access_token', 
	];

	/**
	 * @var string
	 */
	protected $table = 'zoom_tokens';


}
