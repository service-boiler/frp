<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EsbMaintenanceProductGroupRelation extends Model
{
  
    protected $fillable = [
        'product_id', 'esb_maintenance_product_group_id'
    ];

    
    
    protected $table = 'esb_maintenance_product_group_relations';

   

}
