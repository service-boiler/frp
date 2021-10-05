<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class CrmSalesPredictType extends Model
{

    /**
     * @var string
     */
    protected $table = 'crm_sales_predict_types';

    protected $fillable = [
        'id','name'
    ];


}
