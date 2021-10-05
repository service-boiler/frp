<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Concerns\Sortable;


class AcademyProgramStage extends Model
{
    

    use Sortable;
    /**
     * @var string
     */
    protected $table = 'academy_program_stages';

    
    protected $fillable = [
        'stage_id','program_id','sort_order'
    ];

    
   

}
