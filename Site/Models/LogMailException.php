<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class LogMailException extends Model
{

	/**
	 * @var array id
	 */
	protected $fillable = [
		'recipients', 'message'
	];

	/**
	 * @var string
	 */
	protected $table = 'log_mail_exceptions';


}
