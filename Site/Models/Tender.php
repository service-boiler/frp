<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Contracts\Messagable;
use ServiceBoiler\Prf\Site\Facades\Site;


class Tender extends Model implements Messagable
{

	protected $fillable = [
		'manager_id','head_id', 'region_id','source_id','city', 'address','address_name',
		'address_addon', 'tender_category_id','investor_name', 'customer', 'gen_contractor', 'contractors',
		'gen_designer','designers','picker', 'distributor_id', 'distr_contact','distr_contact_phone','distr_contract',
		'planned_purchase_year', 'planned_purchase_month', 'comment', 'rivals','result', 'status_id', 'head_approved_status_id',
		'head_approved_date', 'director_approved_status_id', 'director_approved_date','date_price',
        'rates','rates_object','rates_min','rates_object_min','rates_max','rates_object_max','cb_rate'
	];

	protected $casts = [

		'manager_id'    =>'integer',
        'head_id'       =>'integer',
        'source_id'       =>'integer',
        'region_id'     => 'string',
		'city'          => 'string', 
        'address'       => 'string',
		'address_addon' => 'string',
		'address_name' => 'string',
        'tender_category_id' =>'integer',
		'investor_name' => 'string', 
        'customer'      => 'string',
        'gen_contractor' => 'string',
		'gen_designer'  => 'string',
        'designers'     => 'string',
		'picker'        => 'string', 
        'distributor_id' =>'integer',
        'distr_contract' => 'string',
		'planned_purchase_year'=>'integer',
        'planned_purchase_month'=>'integer',
        'cb_rate'=>'integer',
        'date_price' => 'date:Y-m-d',
        'comment' => 'string',
		'result' => 'string',
        'status_id' =>'integer',
        'head_approved_status_id' =>'integer',
		//'head_approved_date' =>'timestamp',
        'director_approved_status_id' =>'integer',
        //'director_approved_date' =>'timestamp',
	
	];
    
    protected $dates = [
		'director_approved_date',
		'head_approved_date',
		'date_price',
	];


	public function setStatus($status_id)
	{

		if ($status_id == 5 && $this->getOriginal('status_id') != 5) {

			$this->update([
				'status_id' => $status_id,
				'approved_at' => Carbon::now(),

				]);
		} else {
			$this->update(['status_id' => $status_id]);
		}
	}

	public function customers()
    {
        return $this->belongsToMany(
            Customer::class,
            'tender_customer_relations',
            'tender_id',
            'customer_id'
        )->withPivot('id','customer_role_name','customer_contact_id');
    }
	
    //Заказчики строительства
    public function customer_customer()
    {
        return $this->customers()->wherePivot('customer_role_name','customer');
    }
    
    
	public function roles()
    {
        return  CustomerRole::where('display','1')->orderBy('sort_order')->get();
    }
	
    public function roleCustomers($role_name)
    {
        return $this->customers()->wherePivot('customer_role_name',$role_name)->get();
    }

   
	public function tenderProducts()
	{
		return $this->hasMany(TenderProduct::class);
	}
    
	public function products()
	{
		return $this->belongsToMany(Product::class,
			'tender_products',
			'tender_id',
			'product_id');
	}

	public function region()
	{
		return $this->belongsTo(Region::class);
	}

	public function category()
	{
		return $this->belongsTo(TenderCategory::class,'tender_category_id');
	}
	public function distributor()
	{
		return $this->belongsTo(User::class,'distributor_id');
	}
	public function contragent()
	{
		return $this->belongsTo(Contragent::class,'distr_contragent_id');
	}
	public function order()
	{
		return $this->belongsTo(Order::class,'order_id');
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
	 * @return Builder
	 */
	public function statuses()
	{   $user=Auth::user();
        
        if($user->admin == 1) {
        $user_status = 'admin';
        } elseif(in_array($user->email, config('site.director_email'))) {
            $user_status='director';
        } elseif($user->userSubordinates->contains('subordinate_id',$this->manager_id)) {
          $user_status = 'head';
        } elseif($user->id == $this->manager_id) {
          $user_status = 'user';
        } elseif($user->hasRole('admin_tender_worker')) {
          $user_status = 'worker';
        } else {
         $user_status = 'viewer';
        }
        
		return TenderStatus::query()->whereIn('id', config('site.tender_status_transition.' .$user_status . '.' . $this->getAttribute('status_id'), []))->orderBy('sort_order');
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


	public function status()
	{
		return $this->belongsTo(TenderStatus::class);
	}

	public function getProductsCostAttribute()
	{
		return $this->tenderProducts->sum('cost');
        
	}

	public function getApprovedAttribute()
	{
		return in_array($this->status_id,[4,5,6]);
	}

	public function setDatePriceAttribute($value)
	{
		$this->attributes['date_price'] = $value ? Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d') : null;
	}
	
  
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
		return $this->belongsTo(User::class, 'manager_id');
	}

	public function chief()
	{
		return $this->belongsTo(User::class, 'head_id');
	}


	/**
	 * @return string
	 */
	function messageSubject()
	{
		return trans('site::tender.tender') . ' ' . ($this->getAttribute('number') ?: $this->getAttribute('id'));
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageRoute()
	{
		return route((auth()->user()->admin == 1 ? 'admin.' : '') . 'tenders.show', $this);
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageMailRoute()
	{
		return route('admin.tenders.show', $this);
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageStoreRoute()
	{
		return route('admin.tender.message', $this);
	}

   /** @return User */
	function messageReceiver()
	{
		return $this->user->id == auth()->user()->getAuthIdentifier()
			? User::query()->findOrFail(config('site.receiver_id'))
			: $this->user;
	}

    public static function expired()
	{   
        return self::query()
			->whereIn('status_id',['2','3','4','5','9'])
            ->where('planned_purchase_year','<=',Carbon::now()->addDays(15)->format('Y'))
            ->where('planned_purchase_month','<=',Carbon::now()->addDays(15)->format('m'))
            ;
	}
    
    public static function currencyExpired()
	{   
		$current_rate = Currency::where('id','978')->first()->rates;
        
        return self::query()
			->whereIn('status_id',['2','3','4','5','9'])
			->where( function ($q){ $q->where('cb_rate',0)->orWhereNull('cb_rate');})
            ->where( function ($q) use ($current_rate) {
                $q->where('rates_min','>',$current_rate)->orWhere('rates_max','<',$current_rate);
                })
            ;
            
	}
    public static function getCurrencyExpiredAttribute()
	{   
		$current_rate = Currency::where('id','978')->first()->rates;
        //dd($this);
        return in_array($this->status_id,['2','3','4','5','9']) && ($this->rates_min > $current_rate || $this->rates_max<$current_rate);
                
            
	}
    public function getRateCbAttribute()
	{   
		return Currency::where('id','978')->first()->rates;
            
	}


}
