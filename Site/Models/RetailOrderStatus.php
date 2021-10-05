<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class RetailOrderStatus extends Model
{

    /**
     * Заказы
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function retailOrders()
    {
        return $this->hasMany(RetailOrder::class, 'status_id');
    }

}
