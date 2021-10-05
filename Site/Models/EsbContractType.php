<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EsbContractType extends Model
{
  
    protected $fillable = [
        'name', 'template_id', 'color','comments','enabled'
    ];

    
    protected $table = 'esb_contract_types';

    
    
    public function esbContracts()
	{
       return $this->hasOne(
			EsbContract::class,
			'id','type_id'
		);
	}
    public function template()
	{
       return $this->hasOne(
			EsbContractTemplate::class,
			'id','template_id'
		);
	}
    public function user()
	{
       return $this->hasOne(
			User::class,
			'id','user_id'
		);
	}
   

}
