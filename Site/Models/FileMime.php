<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;


class FileMime extends Model
{

    /**
     * @var string
     */
    protected $table = 'file_mimes';
    /**
     * @var array
     */
    protected $fillable = ['name', 'mime', 'accept'];

}
