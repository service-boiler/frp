<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class EsbMaintenanceDistance extends Model
{
  
    protected $fillable = [
        'name', 'cost','active'
    ];

    
    
    protected $table = 'esb_maintenance_distances';

    
    public function regions()
    {
        return $this->belongsToMany(Region::class,'esb_maintenance_distance_relations','esb_maintenance_distance_id','region_id');
    }

}
