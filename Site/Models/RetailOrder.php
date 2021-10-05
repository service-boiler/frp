<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Facades\Site;


class RetailOrder extends Model
{
	protected $table = 'retail_orders';
	
	 protected $fillable = [
        'phone_number','contact','comment','esb_user_id'
    ];


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
		return $this->hasMany(RetailOrderItem::class);
	}


    public function userAddress()
    {
        return $this->belongsTo(Address::class, 'user_address_id');
    }
    
    public function status()
	{
		return $this->belongsTo(RetailOrderStatus::class);
	}
    public function chain()
	{
		return RetailOrder::where('chain_guid',$this->chain_guid);
	}
	
    public function statuses()
	{   
        
        $user=Auth::user();
        
        if($user->id == $this->esb_user_id) {
            $user_status = 'esb_user';
        } else{
            $user_status = 'dealer';
        }
        
		return RetailOrderStatus::query()->whereIn('id', config('site.retail_order_status_transition.' .$user_status . '.' . $this->getAttribute('status_id'), []))->orderBy('sort_order');
	}
}
