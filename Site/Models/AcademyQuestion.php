<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class AcademyQuestion extends Model
{

  
    protected $table = 'academy_questions';

    protected $fillable = [
        'text','enabled'
    ];

    protected $casts = [
        'text'             => 'string',
    ];


    public function answers()
    {
        return $this->hasMany(
            AcademyAnswer::class,
            'question_id');
    }
    public function theme()
    {
        return $this->belongsTo(AcademyTheme::class);
    }
    

}
