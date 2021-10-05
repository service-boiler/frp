<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Contracts\Messagable;

class PartnerPlusRequest extends Model implements Messagable
{
  
    protected $fillable = [
        'partner_id', 'distributor_id','partner_contragent_id','partner_address_id','found_year',
        'sales_staff',
        'shop_count',
        'annual_turnover',
        'warehouse_area',
        'warehouse_count',
        'name_for_site',
        'has_mounters',
        'has_service'
    ];

    
    
    protected $table = 'partner_plus_requests';

    
    public function partner()
	{
       return $this->hasOne(
			User::class,
			'id','partner_id'
		);
	}
    public function distributor()
	{
       return $this->hasOne(
			User::class,
			'id','distributor_id'
		);
	}
    
    
    public function creator()
	{
       return $this->hasOne(
			User::class,
			'id','created_by_id'
		);
	}
    
    public function contragent()
	{
       return $this->hasOne(
			Contragent::class,
			'id','partner_contragent_id'
		);
	}
    
    public function address()
	{
       return $this->hasOne(
			Address::class,
			'id','partner_address_id'
		);
	}
    
    
    public function status()
	{
       return $this->hasOne(
			PartnerPlusRequestStatus::class,
			'id','status_id'
		);
	}
    public function statuses()
	{   
        
        $user=Auth::user();
        
        if($user->hasRole('supervisor') || $user->hasRole('админ')) {
            $user_status = 'supervisor';
        } elseif($user->id == $this->partner_id) {
            $user_status = 'partner';
        } elseif($user->id == $this->distributor_id) {
            $user_status = 'distributor';
        } elseif($user->hasRole('ferroli_user')) {
            $user_status = 'ferroli_user';
        } else{
            $user_status = 'viewer';
        }
      
		return PartnerPlusRequestStatus::query()->whereIn('id', config('site.partner_plus_request_status_transition.' .$user_status . '.' . $this->getAttribute('status_id'), []))->orderBy('sort_order');
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
		return trans('site::user.partner_plus_request.h1') . ' ' . $this->getAttribute('id') .' ' .$this->getAttribute('name_for_site');
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageRoute()
	{
		return route('partner-plus-requests.show', $this);
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageMailRoute()
	{
		return route('partner-plus-requests.show', $this);
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageStoreRoute()
	{
		return route('partner-plus-requests.message', $this);
	}

   /** @return User */
	function messageReceiver()
	{
		return $this->distributor->id == auth()->user()->getAuthIdentifier()
			? User::query()->findOrFail(config('site.receiver_id'))
			: $this->distributor;
	}
    

}
