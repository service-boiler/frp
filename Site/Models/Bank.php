<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{

	/**
	 * @var array id
	 */
	protected $fillable = [
		'ks', 'bank', 'city',
		'address', 'phones', 'inn',
		'disabled', 'date',
	];

	/**
	 * @var string
	 */
	protected $table = 'banks';

	public $incrementing = false;

}
