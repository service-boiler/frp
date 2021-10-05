<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class StandOrderStatus extends Model
{

    public function standOrders()
    {
        return $this->hasMany(StandOrder::class, 'status_id');
    }

}
