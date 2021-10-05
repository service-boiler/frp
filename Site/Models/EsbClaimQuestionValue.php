<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EsbClaimQuestionValue extends Model
{
    
    protected $table = 'esb_claim_question_values';
  
    protected $fillable = [
            'name',
            'text'
    ];

    public function question()
	{
       return $this->hasOne(
			User::class,
			'id','question_id'
		);
	}


   
    

}
