<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Concerns\AttachUserStageQuestions;


class AcademyUserStage extends Model
{
    
    use AttachUserStageQuestions;
    /**
     * @var string
     */
    protected $table = 'academy_user_stages';

  
    protected $fillable = [
       
    ];

    

    public function stage()
    {
        return $this->belongsTo(AcademyStage::class);
    }
    
    public function userStageQuestions()
    {
        return $this->hasMany(
            AcademyUserStageQuestion::class,
            'user_stage_id');
    }
   

}
