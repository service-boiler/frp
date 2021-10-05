<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Concerns\AttachQuestions;


class AcademyTest extends Model
{

    use AttachQuestions;
    /**
     * @var string
     */
    protected $table = 'academy_tests';

    protected $fillable = [
        'name','annotation','count_questions','count_correct','theme_id'
    ];

    protected $casts = [
        'name'             => 'string',
        'annotation'             => 'string',
        'count_questions'          => 'integer',
        'count_correct'          => 'integer',
        'theme_id'          => 'integer',
        
    ];


    public function questions()
    {
        return $this->belongsToMany(
            AcademyQuestion::class,
            'academy_test_questions',
            'test_id',
            'question_id'
        );
    }
    public function theme()
    {
        return $this->belongsTo(AcademyTheme::class);
    }

}
