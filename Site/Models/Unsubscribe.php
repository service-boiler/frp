<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class Unsubscribe extends Model
{

    /**
     * @var string
     */
    protected $table = 'unsubscribes';
    /**
     * @var array
     */
    protected $fillable = ['email'];

}
