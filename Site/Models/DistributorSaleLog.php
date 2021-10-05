<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Mail\DistributorSale\UserDistributorSaleLogCreateEmail;

class DistributorSaleLog extends Model
{

	const TYPE_ERROR = 'error';
	const TYPE_SUCCESS = 'success';

	/**
	 * @var array
	 */
	protected $fillable = ['message', 'url', 'type'];

	/**
	 * @var bool
	 */
	protected $casts = [
		'user_id' => 'integer',
		'message' => 'string',
		'url' => 'string',
		'type' => 'string',
	];

	public static function boot()
	{
		parent::boot();
		static::created(function (DistributorSaleLog $distributorSaleLog) {
			Mail::to($distributorSaleLog->user->email)->send(new UserDistributorSaleLogCreateEmail($distributorSaleLog));
			Mail::to(env('MAIL_DEVEL_ADDRESS'))->send(new UserDistributorSaleLogCreateEmail($distributorSaleLog));
		});
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function getMessageAttribute($value)
	{
		return collect((array)json_decode($value, true));
	}

}
