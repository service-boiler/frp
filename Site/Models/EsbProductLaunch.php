<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ServiceBoiler\Prf\Site\Contracts\Messagable;

class EsbProductLaunch extends Model implements Messagable
{
  
    protected $fillable = [
        'engineer_id', 'contract_id', 'date_launch','comments','esb_user_id','esb_user_product_id','number','launcher_text','approved'
    ];

    protected $casts = [
        
    ];
    
    protected $dates = [
        'date_launch' 
    ];
    
    protected $table = 'esb_product_launches';

    
    public function setDateLaunchAttribute($value)
    {
        $this->attributes['date_launch'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
    }
    public function esbProduct()
	{
       return $this->hasOne(
			EsbUserProduct::class,
			'id','esb_user_product_id'
		);
	}
    public function esbUser()
	{
       return $this->hasOne(
			User::class,
			'id','esb_user_id'
		);
	}
    public function service()
	{
       return $this->hasOne(
			User::class,
			'id','service_id'
		);
	}
    public function engineer()
	{
       return $this->hasOne(
			User::class,
			'id','engineer_id'
		);
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
    function messageRoute()
	{
		return route('esb-product-launches.show',$this);
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageMailRoute()
	{
		return route('esb-product-launches.show',$this);
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageStoreRoute()
	{
		return route('esb-product-launches.show',$this);
	}
    
    function messageReceiver()
	{
		return $this->user->type_id == 4
			? $this->service
			: $this->esbUser;
	}
    
    function messageSubject()
	{
		return trans('site::user.esb_product_launch.launch') . ' №' .$this->getAttribute('id');
	}

}
