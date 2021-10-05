<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EsbAdoContract extends Model
{
    public $incrementing = false;
    /**
     * @var string
     */
    protected $table = 'esb_ado_contracts';

    protected $fillable = [
        'contragent_id', 'valid_to', 'esb_user_product_id',
        'service_id', 'service_inn', 'service_phone',
        'service_name', 'contract_number'
    ];

    protected $casts = [
        'contragent_id' => 'integer',
        'esb_user_product_id' => 'integer',
        'service_id' => 'integer',
        'valid_to'          => 'date:Y-m-d',
        
    ];

    protected $dates = [
        'valid_to',
    ];

    public function setValidToAttribute($value)
    {
        $this->attributes['valid_to'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
    }

    
    public function contragent()
    {
        return $this->belongsTo(Contragent::class);
    }

    public function service()
    {
        return $this->belongsTo(User::class,'id', 'service_id');
    }

    public function esbUserProduct()
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
