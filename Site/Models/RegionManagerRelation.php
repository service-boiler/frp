<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Facades\Site;

class RegionManagerRelation extends Model
{

    protected $fillable = [
        'region_id', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,user_id);
    }

    

}
