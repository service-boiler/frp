<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ServiceBoiler\Prf\Site\Contracts\Messagable;

class EsbUserVisit extends Model implements Messagable
{
  
    protected $fillable = [
        'accepted', 'enabled','type_id','client_user_id','service_user_id','engineer_id','esb_user_product_id','comments','date_planned','cost_planned','cost_actual'
    ];

    protected $casts = [
        'accepted'            => 'integer',
        'enabled'      => 'integer',
    ];
     
    protected $dates = [
        'date_planned' 
    ];
    
    protected $table = 'esb_user_visits';

    public function setDatePlannedAttribute($value)
    {
        $this->attributes['date_planned'] = $value ? Carbon::createFromFormat('d.m.Y H:i', $value) : null;
    }
    // public function getDatePlannedAttribute($value)
    // {
        // return $this->attributes['date_planned']->format('d.m.Y H:i');
    // }
    public function esbUser()
	{
       return $this->hasOne(
			User::class,
			'id','client_user_id'
		);
	}
    public function service()
	{
       return $this->hasOne(
			User::class,
			'id','service_user_id'
		);
	}
    public function engineer()
	{
       return $this->hasOne(
			User::class,
			'id','engineer_id'
		);
	}
    public function esbContract()
	{
       return $this->hasOne(
			EsbContract::class,
			'id','esb_contract_id'
		);
	}
    public function type()
	{
       return $this->hasOne(
			EsbUserVisitType::class,
			'id','type_id'
		);
	}
    public function status()
	{
       return $this->hasOne(
			EsbUserVisitStatus::class,
			'id','status_id'
		);
	}
    public function statuses()
	{   
        
        $user=Auth::user();
        
        if($user->id == $this->client_user_id) {
            $user_status = 'esb_user';
        } else{
            $user_status = 'service';
        }
        
		return EsbUserVisitStatus::query()->whereIn('id', config('site.esb_visit_status_transition.' .$user_status . '.' . $this->getAttribute('status_id'), []))->orderBy('sort_order');
	}
    
    public function esbUserProduct()
	{
       return $this->hasOne(
			EsbUserProduct::class,
			'id','esb_user_product_id'
		);
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
        return route('esb-visits.index');
    }

    /**
     * @return \Illuminate\Routing\Route
     */
    function messageMailRoute()
    {
        return route('esb-visits.index');
    }

    /**
     * @return \Illuminate\Routing\Route
     */
    function messageStoreRoute()
    {
        return route('esb-visits.index');
    }

    function messageReceiver()
    {
        return $this->user->type_id == 4
            ? $this->service
            : $this->esbUser;
    }

    function messageSubject()
    {
        return trans('site::user.esb_user_visit.visit') . ' №' .$this->getAttribute('id');
    }


}
