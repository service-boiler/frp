<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ServiceBoiler\Prf\Site\Contracts\Messagable;

class EsbUserRequest extends Model implements Messagable
{
  
    protected $fillable = [
        'accepted', 'enabled','type_id','recipient_id','user_product_id','esb_user_visit_id','comments','date_planned','contact_name','phone'
    ];

    protected $casts = [
        'accepted'            => 'integer',
        'enabled'      => 'integer',
    ];
     
    protected $dates = [
        'date_planned' 
    ];
    
    protected $table = 'esb_user_requests';

    public function setDatePlannedAttribute($value)
    {
        $this->attributes['date_planned'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
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
			'id','recipient_id'
		);
	}
    public function type()
	{
       return $this->hasOne(
			EsbRequestType::class,
			'id','type_id'
		);
	}
    public function status()
	{
       return $this->hasOne(
			EsbRequestStatus::class,
			'id','status_id'
		);
	}
    public function statuses()
	{   
        
        $user=Auth::user();
        
        if($user->id == $this->esb_user_id) {
            $user_status = 'esb_user';
        } else{
            $user_status = 'service';
        }
        
		return EsbRequestStatus::query()->whereIn('id', config('site.esb_request_status_transition.' .$user_status . '.' . $this->getAttribute('status_id'), []))->orderBy('sort_order');
	}
    
    public function esbUserProduct()
	{
       return $this->hasOne(
			EsbUserProduct::class,
			'id','user_product_id'
		);
	}
    
    public function visit()
	{
       return $this->hasOne(
			EsbUserVisit::class,
			'id','esb_user_visit_id'
		);
	}
    
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
		return route('esb-requests.index',['filter[search_id]'=>$this->id]);
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageMailRoute()
	{
		return route('esb-requests.index');
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageStoreRoute()
	{
		return route('esb-requests.index',['filter[search_id]'=>$this->id]);
	}
    
    function messageReceiver()
	{
		return $this->user->id == auth()->user()->getAuthIdentifier()
			? User::query()->findOrFail(config('site.receiver_id'))
			: $this->user;
	}
    
    function messageSubject()
	{
		return trans('site::user.esb_request.request') . ' №' .$this->getAttribute('id');
	}
}
