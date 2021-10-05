<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{

    /**
     * @var string
     */
    protected $table = 'variables';
    
    protected $fillable = [
        'value', 'comment',
    ];

}
