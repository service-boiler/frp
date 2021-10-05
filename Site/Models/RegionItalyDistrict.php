<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class RegionItalyDistrict extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    
	protected $table = 'region_italy_districts';
	
	protected $fillable = [
        'id', 'name'
    ];

    /**
     * Регионы
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function regions()
    {
        return $this->hasMany(Region::class,'italy_district_id');
    }

}
