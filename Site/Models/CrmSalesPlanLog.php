<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class CrmSalesPlanLog extends Model
{

    /**
     * @var string
     */
    protected $table = 'crm_sales_plan_logs';

    protected $fillable = [
        'user_id','manager_id','text'
    ];



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }


}
