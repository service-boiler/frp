<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Facades\Site;

class TenderStage extends Model
{

    protected $fillable = [
        'tender_id', 'order_id', 'is_paid', 'paid_comments', 
        'is_shipped','shipped_comments','planned_date',
        'shipped_date','doc_id'
    ];
    protected $casts = [
		'tender_id'    =>'integer',
		'order_id'    =>'integer',
		'is_paid'    =>'integer',
		'is_shipped'    =>'integer',
		'paid_comments'    =>'string',
		'shipped_comments'    =>'string',
		'doc_id'    =>'string',
	];
    
    protected $dates = [
		'planned_date',
		'shipped_date',
	];
    
    public function setPlannedDateAttribute($value)
	{
		$this->attributes['planned_date'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
	}
    
    public function setShippedDateAttribute($value)
	{
		$this->attributes['shipped_date'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
	}
    

    
    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

   
	public function tenderStageProducts()
	{
		return $this->hasMany(TenderStageProduct::class);
	}
    

}
