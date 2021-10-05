<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use ServiceBoiler\Prf\Site\Contracts\Bonusable;
use ServiceBoiler\Prf\Site\Contracts\Messagable;


class RetailSaleReport extends Model implements Messagable, Bonusable
{

	/**
	 * @var string
	 */
	protected $table = 'retail_sale_reports';

	/**
	 * @var array
	 */
	protected $fillable = [
		'status_id', 'serial_id', 'product_id', 'bonus',
		'date_trade', 'address_id' ,'address_new', 'contragent_id',
		'country_id',
		'client', 'phone_primary', 'comment',
	];

	/**
	 * @var array
	 */
	protected $casts = [

		'serial_id' => 'string',
		'product_id' => 'string',
		'status_id' => 'integer',
		'contragent_id' => 'integer',
		'bonus' => 'integer',
		'date_trade' => 'date:Y-m-d',
		'address_id' => 'integer',
		'address_new' => 'string',
		'country_id' => 'string',
		'phone_primary' => 'string',
		'client' => 'string',
		'address' => 'string',
		'comment' => 'string',
	];

	/**
	 * @var array
	 */
	protected $dates = [
		'date_trade',
		
	];

	protected static function boot()
	{

		static::creating(function ($model) {
        
			if($model->user->userMotivationSaleStatus == 'basic') {
                $model->setAttribute('bonus', $model->product->product_retail_sale_bonus->value);
            } elseif($model->user->userMotivationSaleStatus == 'start') {
                $model->setAttribute('bonus', $model->product->product_retail_sale_bonus->start);
            } elseif($model->user->userMotivationSaleStatus == 'profi') {
                $model->setAttribute('bonus', $model->product->product_retail_sale_bonus->profi);
            } elseif($model->user->userMotivationSaleStatus == 'super') {
                $model->setAttribute('bonus', $model->product->product_retail_sale_bonus->super);
            }
     
		});

	}

	/**
	 * @param $value
	 */
	public function setDateTradeAttribute($value)
	{
		$this->attributes['date_trade'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
	}

	
	/**
	 * @param $value
	 *
	 * @return mixed|null
	 */
	public function getPhonePrimaryAttribute($value)
	{
		return $value ? preg_replace(config('site.phone.get.pattern'), config('site.phone.get.replacement'), $value) : null;
	}

	/**
	 * @param $value
	 */
	public function setPhonePrimaryAttribute($value)
	{
		$this->attributes['phone_primary'] = $value ? preg_replace(config('site.phone.set.pattern'), config('site.phone.set.replacement'), $value) : null;
	}

	

	public function getTotalAttribute()
	{
		return $this->getAttribute('bonus') + $this->getAttribute('enabled_social_bonus');
	}

	/**
	 * Файлы
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\morphMany
	 */
	public function files()
	{
		return $this->morphMany(File::class, 'fileable');
	}

	public function detachFiles()
	{
		foreach ($this->files as $file) {
			$file->fileable_id = null;
			$file->fileable_type = null;
			$file->save();
		}
	}

	/**
	 * Серийный номер
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function serial()
	{
		return $this->belongsTo(Serial::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function status()
	{
		return $this->belongsTo(MountingStatus::class);
	}


	/**
	 * Страна
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function country()
	{
		return $this->belongsTo(Country::class);
	}

	/**
	 * Пользователь
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}
	public function address()
	{
		return $this->belongsTo(Address::class);
	}


	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function product()
	{
		return $this->belongsTo(Product::class);
	}

    public function duplicates_serial() {
            return $this
                ->hasMany(RetailSaleReport::class, 'serial_id', 'serial_id')
                ->whereNotNull('serial_id')
                ->where('id', '!=', $this->getKey());
    }


	public function statuses()
	{
		$digiftBonus = $this->digiftBonus();

		if ($digiftBonus->where('blocked', 1)->exists()) {
			return collect();
		}

		$statuses = MountingStatus::query()->where('id', '!=', $this->getAttribute('status_id'));

		if ($digiftBonus->doesntExist()) {
			return $statuses->getQuery()->get();
		}


		return RetailSaleReportStatus::query()
			->when($digiftBonus->doesntExist(),
				function ($query) {
					return $query->where('id', '!=', $this->getAttribute('status_id'));
				},
				function ($query) use ($digiftBonus) {
					$query->when($digiftBonus->where('blocked', 0),
						function ($query) {

						},
						function () {

						}
					);
				})
			->orderBy('sort_order');
	}

	/**
	 * Бонусы Digift
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphOne
	 */
	public function digiftBonus()
	{
		return $this->morphOne(DigiftBonus::class, 'bonusable');
	}

	/**
	 * Сообщения
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function messages(): MorphMany
	{
		return $this->morphMany(Message::class, 'messagable');
	}

	/**
	 * @return MorphMany
	 */
	public function publicMessages()
	{
		return $this->messages()->where('personal', 0);
	}

	/**
	 * @return string
	 */
	function messageSubject()
	{
		return ucfirst(trans('site::mounting.mounting')) . ' № ' . $this->getAttribute('id');
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageRoute()
	{
		return route((auth()->user()->admin == 1 ? 'admin.' : '') . 'retail-sale-reports.show', $this);
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageMailRoute()
	{
		return route((auth()->user()->admin == 1 ? '' : 'admin.') . 'retail-sale-reports.show', $this);
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageStoreRoute()
	{
		return route('retail-sale-reports.message', $this);
	}

	/** @return User */
	function messageReceiver()
	{
		return $this->user->id == auth()->user()->getAuthIdentifier()
			? User::query()->findOrFail(config('site.receiver_id'))
			: $this->user;
	}

	/**
	 * @return string
	 */
	function bonusCreateMessage()
	{
		return 'Для начисления бонусов необходимо одобрить отчет о продаже';
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function bonusStoreRoute()
	{
		return route('admin.retail-sale-reports.digift-bonuses.store', $this);
	}
}
