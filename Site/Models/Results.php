<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class Results extends Model
{

    /**
     * @var string
     */
    protected $table = 'results';

    protected $fillable = [
        'value_int','type_id','period_type','date_actual'
    ];

    protected $casts = [
        'value_int'          => 'integer',
        'date_actual'   => 'date:Y-m-d',
        
    ];


    public function presentations()
    {
        return $this->belongsToMany(
            AcademyPresentation::class,
            'academy_stage_presentations',
            'stage_id',
            'presentation_id'
        )->orderBy('academy_presentations.name');
        //В списке презентаций в home сортировка сначала но Stage
    }

    public function tests()
    {
        return $this->belongsToMany(
            AcademyTest::class,
            'academy_stage_tests',
            'stage_id',
            'test_id'
        );
    }

    public function videos()
    {
        return $this->belongsToMany(
            AcademyVideo::class,
            'academy_stage_videos',
            'stage_id',
            'video_id'
        );
    }
    public function parentStage()
    {
        return $this->belongsTo(AcademyStage::class,'parent_stage_id');
    }
    public function theme()
    {
        return $this->belongsTo(AcademyTheme::class);
    }

    public function userStage(User $user)
    {
        return $this->belongsTo(AcademyUserStage::class,'id','stage_id')->where('user_id',$user->id);
    }


}
