<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EsbClaimQuestionAnswer extends Model
{
    
    protected $table = 'esb_claim_question_answers';
  
    protected $fillable = [
            'claim_id',
            'question_id',
            'value_id',
            'value_text',
    ];

    public function question()
	{
       return $this->hasOne(
           EsbClaimQuestion::class,
			'id','question_id'
		);
	}
    public function questionValue()
	{
       return $this->hasOne(
           EsbClaimQuestionValue::class,
			'id','value_id'
		);
	}
    public function getValueAttribute()
	{
       if($this->questionValue){
           return $this->questionValue->name;
       } else {
           return $this->value_text;
       }
	}

   
   
    

}
