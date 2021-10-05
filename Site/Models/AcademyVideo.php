<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class AcademyVideo extends Model
{

    /**
     * @var string
     */
    protected $table = 'academy_videos';

    
    protected $fillable = [
        'name','annotation','link',
    ];
    
    public function stages()
    {
        return $this->belongsToMany(
            AcademyStage::class, 
            'academy_stage_videos',
            'video_id','stage_id'
            );
    }
    
}
