<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRole extends Model
{

    /**
     * @var string
     */
    protected $table = 'customer_roles';
    
    protected $fillable = [
        'name', 'title', 'description', 'display',
    ];

}
