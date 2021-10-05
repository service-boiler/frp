<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class EsbCatalogService extends Model
{
    protected $table = 'esb_catalog_services';

    protected $fillable = [
        'name', 'type_id', 'enabled','brand_id'
    ];


    public function type()
    {
        return $this->belongsTo(EsbCatalogServiceType::class, 'type_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function prices()
    {
        return $this->hasMany(
            EsbCatalogServicePrice::class,'service_id','id'
        );
    }

    public function getCostStdAttribute()
    {

        return $this->prices()->where('company_id',1)->exists() ? $this->prices()->where('company_id',1)->first()->price : null;
    }

}
