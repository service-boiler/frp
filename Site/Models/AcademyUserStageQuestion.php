<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;



class AcademyUserStageQuestion extends Model
{
    
    protected $table = 'academy_user_stage_questions';

    
    protected $fillable = [
        'user_id','user_stage_id','question_id','answer_id','is_correct'
    ];

    

    public function stage()
    {
        return $this->belongsTo(AcademyStage::class);
    }

    public function question()
    {
        return $this->belongsTo(AcademyQuestion::class);
    }
    
    public function answer()
    {
        return $this->belongsTo(AcademyAnswer::class);
    }
    
   

}
