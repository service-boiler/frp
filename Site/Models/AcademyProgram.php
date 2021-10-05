<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Concerns\AttachStages;
use ServiceBoiler\Prf\Site\Concerns\Sortable;


class AcademyProgram extends Model
{
    
    use AttachStages;
    use Sortable;
    /**
     * @var string
     */
    protected $table = 'academy_programs';

    public $timestamps = false;
    
    protected $fillable = [
        'name','annotation','enabled','theme_id','bonuses'
    ];

    protected $casts = [
        'name'             => 'string',
        'annotation'             => 'string',
        'enabled'             => 'integer',
        'bonuses'             => 'integer',
        'theme_id'             => 'integer',
        
    ];


    public function theme()
    {
        return $this->belongsTo(AcademyTheme::class);
    }
    
    public function stages()
    {
        return $this->belongsToMany(
            AcademyStage::class,
            'academy_program_stages',
            'program_id',
            'stage_id'
        )->withPivot('id','sort_order');
    }

   

}
