<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class EsbContractFieldRelation extends Model
{
  
    protected $fillable = [
        'esb_contract_field_id', 'esb_contract_id', 'value'
    ];

    
    protected $table = 'esb_contract_field_relations';


   

}
