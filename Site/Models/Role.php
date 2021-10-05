<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    /**
     * @var string
     */
    protected $table = 'roles';
    
    protected $fillable = [
        'name', 'title', 'description', 'display', 'authorization_enabled', 'authorization_name', 'authorization_header', 'role_fl',
    ];

}
