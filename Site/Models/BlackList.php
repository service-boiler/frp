<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class BlackList extends Model
{
    protected $fillable = [
        'web', 'name', 'country_id','region_id','full','active','comment'
    ];

    /**
     * @var string
     */
    protected $table = 'black_list_addresses';

 
}
