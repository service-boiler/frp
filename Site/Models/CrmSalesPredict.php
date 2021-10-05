<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class CrmSalesPredict extends Model
{

    /**
     * @var string
     */
    protected $table = 'crm_sales_predicts';

    protected $fillable = [
        'user_id','contragent_id','period_type_id','period_num','year','created_by','enabled','comments','predict_type_id','value'
    ];

    protected $casts = [
        'user_id'          => 'integer',
        'contragent_id'          => 'integer',
        'period_type_id'          => 'srting',
        'predict_type_id'          => 'srting',
        'period_num'          => 'integer',
        'year'          => 'integer',
        'created_by'          => 'integer',
        'enabled'          => 'integer',
        
    ];


    protected static function boot()
    {
        static::creating(function ($model) {

            CrmSalesPlanLog::create(['text'=>'План добавлен ' .moneyFormatEuro($model->value), 'user_id'=>$model->user_id, 'manager_id'=>$model->created_by]);
        });
        static::updating(function ($model) {

            CrmSalesPlanLog::create(['text'=>'План' .($model->enabled ? ' утвержден' : ' обновлен на')  .moneyFormatEuro($model->value), 'user_id'=>$model->user_id, 'manager_id'=>$model->created_by]);
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(CrmSalesPredictType::class,'predict_type_id');
    }

}
