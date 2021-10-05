<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class EsbCatalogServicePrice extends Model
{
    protected $table = 'esb_catalog_service_prices';

    protected $fillable = [
        'service_id', 'price','enabled'
    ];


    public function service()
    {
        return $this->belongsTo(EsbCatalogService::class, 'service_id');
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

}
