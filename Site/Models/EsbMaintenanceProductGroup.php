<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class EsbMaintenanceProductGroup extends Model
{
  
    protected $fillable = [
        'name', 'cost_single', 'cost_year','active'
    ];

    
    
    protected $table = 'esb_maintenance_product_groups';

    
    public function products()
    {
        return $this->belongsToMany(Product::class,'esb_maintenance_product_group_relations','esb_maintenance_product_group_id','product_id');
    }

}
