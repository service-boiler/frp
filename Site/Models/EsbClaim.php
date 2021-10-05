<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EsbClaim extends Model
{
    
    protected $table = 'esb_claims';
  
    protected $fillable = [
            'ticket_id',
            'esb_user_product_id',
            'claimer_id',
            'claimer_name',
            'claimer_phone',
            'claimer_address',
            'claimer_contact',
            'product_id',
            'product_name',
            'product_serial',
            'date_sale',
            'date_launch',
            'sale_name',
            'mounter_id',
            'mounter_name',
            'launcher_id',
            'launcher_name',
            'launch_comment',
            'claim_text',
            'heat_system',
            'electric_system',
            'heat_area',
            'smokestack',
            'smokestack_type'   
    ];

    protected $dates = [
            'date_sale',
            'date_launch',
    ];

    
    public function setDateLaunchAttribute($value)
    {
        $this->attributes['date_launch'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
    }
    public function setDateSaleAttribute($value)
    {
        $this->attributes['date_sale'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
    }
    
    public function claimer()
	{
       return $this->hasOne(
			User::class,
			'id','claimer_id'
		);
	}
    public function product()
	{
       return $this->hasOne(
			Product::class,
			'id','product_id'
		);
	}
    
    
    public function mounter()
	{
       return $this->hasOne(
			User::class,
			'id','mounter_id'
		);
	}
    
    public function launcher()
	{
       return $this->hasOne(
			User::class,
			'id','launcher_id'
		);
	}


    public function answers()
    {
        return $this->hasMany(
            EsbClaimQuestionAnswer::class,
            'claim_id');
    }

    

}
