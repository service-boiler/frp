<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class AcademyTheme extends Model
{

    /**
     * @var string
     */
    protected $table = 'academy_themes';
    public $timestamps = false;
    
    protected $fillable = [
        'name',
    ];

    
}
