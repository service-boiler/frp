<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Contracts\Messagable;
use ServiceBoiler\Prf\Site\Concerns\Schedulable;
use ServiceBoiler\Prf\Site\Contracts\SingleFileable;
use Site;


class StandOrder extends Model implements Messagable, SingleFileable
{

	use Schedulable;

	protected $fillable = [
		'status_id',
		'contragent_id',
		'address_id',
		'contacts_comment',
		'warehouse_address_id',
		'shipped_at',
	];

     protected $casts = [

	'shipped_at' => 'date:Y-m-d',
	
    ];
    
    protected $dates = [
		'shipped_at',
	];
    
        
    public function setShippedAtAttribute($value)
	{   
		$this->attributes['shipped_at'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
	}
	/**
	 * @param $currency_id
	 * @param bool $rounded
	 * @param bool $format
	 *
	 * @return mixed
	 */
	public function total($currency_id, $rounded = true, $format = false)
	{

		$currency = Currency::query()->findOrFail($currency_id);
		$sum = $this->items->sum(function ($item) use ($currency_id) {
			return Site::convert($item->price, $item->currency_id, is_null($currency_id) ? $item->currency_id : $currency_id, $item->quantity, false);
		});
		if ($rounded === true) {
			$sum = Site::round($sum);
		}

		if ($format === false) {
			return $sum;
		}

		return Site::formatPrice($sum, $currency);

	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphOne
	 */
	public function file()
	{
		return $this->morphOne(File::class, 'fileable')->where('type_id',config('site.stand_order_bill_file_type'));
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
	 * @param int $currency_id
	 */
	public function recalculate($currency_id = 643)
	{
		/** @var OrderItem $item */
		foreach ($this->items as $item) {
			$item->recalculate($currency_id);
		}
	}

	/**
	 * @return mixed
	 */
	public function quantity()
	{
		return $this->items->sum('quantity');
	}


	/**
	 * Order items
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function items()
	{
		return $this->hasMany(StandOrderItem::class);
	}

	/**
	 * Контрагент
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function contragent()
	{
		return $this->belongsTo(Contragent::class);
	}

	/**
	 * Склад отгрузки
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function warehouse_address()
	{
		return $this->belongsTo(Address::class);
	}
    
	public function address()
	{
		return $this->belongsTo(Address::class);
	}

	public function hasGuid()
	{
		return !is_null($this->getAttribute('guid'));
	}

	/**
	 * Сообщения
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function messages()
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
	 * User
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function order()
	{
		return $this->belongsTo(Order::class,'order_id');
	}
	/**
	 * Status
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function status()
	{
		return $this->belongsTo(StandOrderStatus::class);
	}
    
    public function statuses()
	{   $user=Auth::user();
        
        if($user->name == 'Администратор') {
        $user_status = 'admin';
        } elseif(in_array($user->email, config('site.director_email'))) {
            $user_status='director';
        } elseif($user == $this->user->region->manager) {
          $user_status = 'manager';
        }  elseif($user->hasRole('supervisor_stand_orders')) {
          $user_status = 'head';
        } elseif($user->userSubordinates->contains('subordinate_id',$this->manager_id)) {
          $user_status = 'head';
        } elseif($user->id == $this->warehouse_address->user->id) {
          $user_status = 'distributor';
        } elseif($user->id == $this->user_id) {
          $user_status = 'user';
        }  else {
         $user_status = 'viewer';
        }
       // dd($user_status);
		return StandOrderStatus::query()->whereIn('id', config('site.stand_order_status_transition.' .$user_status . '.' . $this->getAttribute('status_id'), []))->orderBy('sort_order');
	}
	/**
	 * @return string
	 */
	function messageSubject()
	{
		return trans('site::stand_order.order') . ' ' . $this->id;
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageRoute()
	{
		if (auth()->user()->admin == 1) {
			$route = 'admin.stand-orders.show';
		} else {
			$route = 'stand-orders.show';
		}

		return route($route, $this);
	}

	
	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageMailRoute()
	{
		if ($this->address->addressable->id == auth()->user()->getAuthIdentifier()) {
			$route = 'stand-orders.show';
		} elseif ($this->address->addressable->admin == 1) {
			$route = 'stand-orders.show';
		} else {
			$route = 'stand-orders.show';
		}

		return route($route, $this);

	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageStoreRoute()
	{
		return route('stand-orders.message', $this);
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
	function fileStorage()
	{
		return 'payments';
	}
}
