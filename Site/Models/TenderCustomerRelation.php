<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class TenderCustomerRelation extends Model
{

    /**
     * @var string
     */
    protected $table = 'tender_customer_relations';
    
    public function role()
	{
		return $this->belongsTo(CustomerRole::class,'id','customer_role_id');
	}

}
