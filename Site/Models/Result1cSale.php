<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class Result1cSale extends Model
{

    /**
     * @var string
     */
    protected $table = 'result_1c_sales';

    protected $fillable = [
        'value','type_id','period_type','date_actual'
    ];

    protected $casts = [
        'value'          => 'integer',
        'date_actual'   => 'date:Y-m-d',
        
    ];


    public function contragent()
    {
        return $this->belongsTo(Contragent::class,'contragent_inn','inn');
    }


}
