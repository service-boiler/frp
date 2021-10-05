<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EsbRepair extends Model
{
  
    protected $fillable = [
        'engineer_id',
        'date_repair',
        'esb_user_product_id',
        'engineer_id',
        'client_id',
        'user_name',
        'address',
        'product_id',
        'product_name',
        'product_serial',
        'cost',
        'parts_cost',
        'comments',
        'phone',
        'number',
    ];
    
    protected $dates = [
        'date_repair' 
    ];
    
    protected $table = 'esb_repairs';

    
    public function setDateRepairAttribute($value)
    {
        $this->attributes['date_repair'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
    }
    
    public function service()
	{
       return $this->hasOne(
			User::class,
			'id','service_id'
		);
	} 
    
    public function client()
	{
       return $this->hasOne(
			User::class,
			'id','client_id'
		);
	}
    
    public function engineer()
	{
       return $this->hasOne(
			User::class,
			'id','engineer_id'
		);
	} 
    
    public function esbProduct()
	{
       return $this->hasOne(
			EsbUserProduct::class,
			'id','esb_user_product_id'
		);
	}
    
    public function parts()
	{
		return $this->hasMany(EsbPart::class);
	}
    
    

}
