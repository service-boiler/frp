<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Concerns\Sortable;

class AcademyPresentationSlide extends Model
{

    use Sortable;
  
    protected $table = 'academy_presentation_slides';

    protected $fillable = [
        'name','presentation_id','text','image_id','sound_id','enabled','sort_order'
    ];

    protected $casts = [
        'text'             => 'string',
    ];


    public function presentation()
    {
        return $this->belongsTo(
            AcademyPresentation::class,
            'presentation_id');
    }
    
    public function image()
    {
        return $this->belongsTo(Image::class,'image_id','id');
    }
    
    public function sound()
    {   
        return $this->belongsTo(File::class,'sound_id','id');
        
    }
    
    function imageStorage()
    {
        return 'presentations';
    }

}
