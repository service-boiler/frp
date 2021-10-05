<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class UserVar extends Model
{
  
    protected $fillable = [
        'variable_name', 'variable_value',
    ];

    protected $table = 'user_vars';

    

}
