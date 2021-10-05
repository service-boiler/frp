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


class Ticket extends Model implements Messagable
{

	protected $fillable = [
		'type_id','receiver_type_id','receiver_id','theme_id','text',
        'closed_by_id','status_id','consumer_name','consumer_email',
        'consumer_phone','consumer_company','consumer_company_id',
        'locality','region_id'
	];

    
	protected $dates = [
		'closed_at',
	];
	public function status()
	{
		return $this->belongsTo(TicketStatus::class);
	}
    
	public function receiver()
	{
		return $this->belongsTo(User::class,'receiver_id');
	}
	public function createdBy()
	{
		return $this->belongsTo(User::class,'created_by_id');
	}
    
	public function type()
	{
		return $this->belongsTo(TicketType::class);
	}
	public function theme()
	{
		return $this->belongsTo(TicketTheme::class);
	}
	public function region()
	{
		return $this->belongsTo(Region::class,'region_id');
	}
    
	public function customers()
    {
        return $this->belongsToMany(
            Customer::class,
            'ticket_customer_relations',
            'ticket_id',
            'customer_id'
        )->withPivot('id','customer_role_name','customer_contact_id');
    }
	public function roles()
    {
        return  CustomerRole::where('display','1')->orderBy('sort_order')->get();
    }
	
    
	/**
	 * @return string
	 */
	function messageSubject()
	{
		return trans('site::ticket.ticket') . ' ' . ($this->getAttribute('number') ?: $this->getAttribute('id'));
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageRoute()
	{
		return route((auth()->user()->admin == 1 ? 'admin.' : '') . 'tickets.show', $this);
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageMailRoute()
	{
		return route('admin.tickets.show', $this);
	}

	/**
	 * @return \Illuminate\Routing\Route
	 */
	function messageStoreRoute()
	{
		return route('admin.ticket.message', $this);
	}

	/** @return User */
	function messageReceiver()
	{
		return $this->receiver->id == auth()->user()->getAuthIdentifier()
			? User::query()->findOrFail(config('site.receiver_id'))
			: $this->receiver;
	}
    
    public function statuses()
	{   $user=Auth::user();
        
        if($user->admin == 1) {
        $user_status = 'admin';
        } elseif($user->id == $this->manager_id) {
          $user_status = 'user';
        } elseif($user->id == $this->receiver_id) {
          $user_status = 'user';
        } elseif($user->hasRole('supervisor')) {
          $user_status = 'supervisor';
        } else {
         $user_status = 'viewer';
        }
        
		return TicketStatus::query()->whereIn('id', config('site.ticket_status_transition.' .$user_status . '.' . $this->getAttribute('status_id'), []))->orderBy('sort_order');
	}
    
    public static function expired()
	{   
		return self::query()
			->whereIn('status_id',['1','2','3'])
            ->where('created_at','<=',Carbon::now()->subDays(8))
            ;
	}
    
    public static function waiting()
	{   
		return self::query()
			->whereIn('status_id',['1','2'])
            ->where('created_at','<=',Carbon::now()->subDays(1))
            ;
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


}
