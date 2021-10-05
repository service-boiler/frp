<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class AcademyAnswer extends Model
{

  
    protected $table = 'academy_answers';

    protected $fillable = [
        'text','enabled','question_id','is_correct'
    ];

    protected $casts = [
        'name'             => 'string',
        'annotation'             => 'string',
        'count_questions'          => 'integer',
        'count_correct'          => 'integer',
        'theme_id'          => 'integer',
        
    ];


    public function answers()
    {
        return $this->belongsToMany(
            AcademyAnswers::class,
            'id',
            'question_id'
        );
    }
    public function theme()
    {
        return $this->belongsTo(AcademyTheme::class);
    }

    public function setIsCorrectAttribute($value)
    {   
       $this->attributes['is_correct'] = $value ? '1' : null;
    }
}
