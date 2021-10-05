<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Facades\Site;

class UserBizRegionRelation extends Model
{

    protected $fillable = [
        'biz_district_id', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,user_id);
    }

    

}
