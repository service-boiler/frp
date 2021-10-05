<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class CacheIpLocation extends Model
{

  
    protected $table = 'cache_ip_location';

    protected $fillable = [
        'ip','region_iso','city','country_iso'
    ];

}
