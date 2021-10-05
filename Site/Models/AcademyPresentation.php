<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class AcademyPresentation extends Model
{

  
    protected $table = 'academy_presentations';

    protected $fillable = [
        'name','annotation','text','enabled'
    ];

    protected $casts = [
        'text'             => 'string',
    ];


    public function slides()
    {
        return $this->hasMany(
            AcademyPresentationSlide::class,
            'presentation_id')->orderBy('sort_order');
    }
    
    public function stages()
    {
        return $this->belongsToMany(
            AcademyStage::class, 
            'academy_stage_presentations',
            'presentation_id','stage_id'
            );
    }
    

}
