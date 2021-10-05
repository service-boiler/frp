<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class CrmSalesPlan extends Model
{

    /**
     * @var string
     */
    protected $table = 'crm_sales_plans';

    protected $fillable = [
        'user_id','contragent_id','period_type_id','period_num','year','created_by','enabled','comments','value'
    ];

    protected $casts = [
        'user_id'          => 'integer',
        'contragent_id'          => 'integer',
        'period_type_id'          => 'srting',
        'period_num'          => 'integer',
        'year'          => 'integer',
        'created_by'          => 'integer',
        'enabled'          => 'integer',
        
    ];

    protected static function boot()
    {
        static::creating(function ($model) {

            CrmSalesPlanLog::create(['text'=>
                 ($model->period_type_id=='month' ? trans('site::messages.months.' .$model->period_num) : '') .' ' .$model->year .' '
                .' План добавлен ' .moneyFormatEuro($model->value)
                , 'user_id'=>$model->user_id, 'manager_id'=>$model->created_by]);
        });
        static::updating(function ($model) {

            CrmSalesPlanLog::create(['text'=>
                ($model->period_type_id=='month' ? trans('site::messages.months.' .$model->period_num) : '') .' '.$model->year .' '
                .' План' .($model->enabled ? ' утвержден' : ' обновлен на')  .moneyFormatEuro($model->value) ,
                'user_id'=>$model->user_id, 'manager_id'=>$model->created_by]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
