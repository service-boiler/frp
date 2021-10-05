<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class DistributorSaleUrl extends Model
{

    /**
     * @var array
     */
    
    protected static function boot()
	{
	
	}

    protected $fillable = ['user_id', 'url', 'enabled','tried_at'];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
