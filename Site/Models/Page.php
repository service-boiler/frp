<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'h1', 'route'
    ];

}
