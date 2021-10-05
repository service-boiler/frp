<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EsbProductMaintenance extends Model
{
  
    protected $fillable = [
        'engineer_id', 'contract_id', 'date','comments','esb_user_product_id','number','active'
    ];

    protected $casts = [
        'accepted'            => 'integer',
        'active'      => 'integer',
    ];
    
    protected $dates = [
        'date' 
    ];
    
    protected $table = 'esb_product_maintenances';

    
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
    }
    
    public function service()
	{
       return $this->hasOne(
			User::class,
			'id','service_id'
		);
	} 
    
    public function esbProduct()
	{
       return $this->hasOne(
			EsbUserProduct::class,
			'id','esb_user_product_id'
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
    

}
