<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{

    /**
     * @var string
     */
    protected $table = 'menu_items';
    
    protected $fillable = [
        'href', 'name','menu_id'
    ];

}
