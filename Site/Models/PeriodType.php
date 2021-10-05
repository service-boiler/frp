<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodType extends Model
{
    /**
     * @var string
     */
    protected $table = 'period_types';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function crmSalesPlans()
    {
        return $this->hasMany(CrmSalesPlan::class, 'period_type_id');

    }

}
