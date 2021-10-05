<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class EsbContractField extends Model
{
  
    protected $fillable = [
        'name', 'title', 'shared','enabled'
    ];

    
    protected $table = 'esb_contract_fields';


    public function esbContracts()
	{
       return $this->belongsToMany(
			EsbContract::class, 'esb_contract_field_relations',
			'esb_contract_field_id','esb_contract_id'
		);
	}
    public function template()
    {
        return $this->belongsToMany(
            EsbContract::class, 'esb_contract_template_field_relations',
            'esb_contract_field_id','esb_contract_template_id'
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
