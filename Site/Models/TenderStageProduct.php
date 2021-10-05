<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Facades\Site;

class TenderStageProduct extends Model
{

    protected $fillable = [
        'stage_id', 'tender_product_id', 'count'
    ];
    protected $casts = [

		'stage_id'    =>'integer',
		'tender_product_id'    =>'integer',
		'count'    =>'integer',
		
	
	];
   
   
    public function tenderProduct()
    {
        return $this->belongsTo(TenderProduct::class);
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    
}
