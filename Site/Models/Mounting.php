<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use ServiceBoiler\Prf\Site\Contracts\Bonusable;
use ServiceBoiler\Prf\Site\Contracts\Messagable;


class Mounting extends Model implements Messagable, Bonusable
{

	/**
	 * @var string
	 */
	protected $table = 'mountings';

	/**
	 * @var array
	 */
	protected $fillable = [
		'status_id', 'serial_id', 'product_id',
		'source_id', 'source_other',
		'bonus_social', 'bonus',
		'date_trade', 'date_mounting',
		'engineer_id', 'certificate_id', 'trade_id', 'contragent_id',
		'source_id', 'country_id',
		'client', 'phone_primary', 'phone_secondary',
		'address', 'social_url',
		'social_enabled', 'comment',
	];

	/**
	 * @var array
	 */
	protected $casts = [

		'serial_id' => 'string',
		'product_id' => 'string',
		'source_id' => 'integer',
		'source_other' => 'string',
		'status_id' => 'integer',
		'contragent_id' => 'integer',
		'bonus' => 'integer',
		'bonus_social' => 'integer',
		'date_trade' => 'date:Y-m-d',
		'date_mounting' => 'date:Y-m-d',
		'engineer_id' => 'integer',
		'trade_id' => 'integer',
		'certificate_id' => 'string',
		'country_id' => 'string',
		'phone_primary' => 'string',
		'phone_secondary' => 'string',
		'social_url' => 'string',
		'client' => 'string',
		'address' => 'string',
		'social_enabled' => 'boolean',
		'comment' => 'string',
	];

	/**
	 * @var array
	 */
	protected $dates = [
		'date_trade',
		'date_mounting',
	];

	protected static function boot()
	{

		static::creating(function ($model) {
            
			/** @var Mounting $model */
			if($model->user->userMotivationStatus == 'basic') {
                $model->setAttribute('bonus', $model->product->mounting_bonus->value);
            } elseif($model->user->userMotivationStatus == 'start') {
                $model->setAttribute('bonus', $model->product->mounting_bonus->start);
            } elseif($model->user->userMotivationStatus == 'profi') {
                $model->setAttribute('bonus', $model->product->mounting_bonus->profi);
            } elseif($model->user->userMotivationStatus == 'super') {
                $model->setAttribute('bonus', $model->product->mounting_bonus->super);
            }
            
			            
            $model->setAttribute('social_bonus', $model->product->mounting_bonus->social);
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
	 */
	public function setDateMountingAttribute($value)
	{
		$this->attributes['date_mounting'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
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

	/**
	 * @param $value
	 *
	 * @return mixed|null
	 */
	public function getPhoneSecondaryAttribute($value)
	{
		return $value ? preg_replace(config('site.phone.get.pattern'), config('site.phone.get.replacement'), $value) : null;
	}

	/**
	 * @param $value
	 */
	public function setPhoneSecondaryAttribute($value)
	{
		$this->attributes['phone_secondary'] = $value ? preg_replace(config('site.phone.set.pattern'), config('site.phone.set.replacement'), $value) : null;
	}

	public function getTotalAttribute()
	{
		return $this->getAttribute('bonus') + $this->getAttribute('enabled_social_bonus');
	}

	public function getEnabledSocialBonusAttribute()
	{
		return $this->getAttribute('social_enabled') == 1 ? $this->getAttribute('social_bonus') : 0;
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
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function source()
	{
		return $this->belongsTo(MountingSource::class);
	}

	/**
	 * Акт выполненных работ
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function act()
	{
		return $this->belongsTo(Act::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function contragent()
	{
		return $this->belongsTo(Contragent::class)->withDefault();
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

	/**
	 * Торговая организация
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function trade()
	{
		return $this->belongsTo(Trade::class);
	}

	/**
	 * Сервисный инженер
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function engineer()
	{
		return $this->belongsTo(Engineer::class)->withDefault();;
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
                    ->hasMany(Mounting::class, 'serial_id', 'serial_id')
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


		return MountingStatus::query()
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
		return route((auth()->user()->admin == 1 ? 'admin.' : '') . 'mountings.show', $this);
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageMailRoute()
	{
		return route((auth()->user()->admin == 1 ? '' : 'admin.') . 'mountings.show', $this);
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageStoreRoute()
	{
		return route('mountings.message', $this);
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
		return 'Для начисления бонусов необходимо одобрить отчет по монтажу';
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function bonusStoreRoute()
	{
		return route('admin.mountings.digift-bonuses.store', $this);
	}
}
