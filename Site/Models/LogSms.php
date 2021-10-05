<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class LogSms extends Model
{

	/**
	 * @var array id
	 */
	protected $fillable = [
		'phone', 'text'
	];

	/**
	 * @var string
	 */
	protected $table = 'log_sms';


}
