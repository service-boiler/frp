<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EsbClaimQuestion extends Model
{
    
    protected $table = 'esb_claim_questions';
  
    protected $fillable = [
            'name',
            'type_id',
            'value_id',
            'value_text',

    ];

    public function questionValues()
	{
       return $this->hasMany(
			EsbClaimQuestionValue::class,
			'question_id'
		);
	}
   
    

}
