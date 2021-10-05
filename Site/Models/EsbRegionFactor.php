<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EsbRegionFactor extends Model
{
  
    protected $fillable = [
        'region_id', 'maintenance_factor', 'transport_factor'
    ];

    
    
    protected $table = 'esb_region_factors';

   

}
