<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{

    /**
     * @var string
     */
    protected $table = 'brands';

    public function equipments(){
        return $this->belongsToMany(Equipment::class);
    }


}
