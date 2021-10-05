<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MissionClient extends Model
{

	/**
	 * @var string
	 */
	protected $table = 'mission_clients';
    
    public $timestamps = FALSE;
    /**
     * @var array
     */
    protected $fillable = [
        'mission_id', 
        'comment',
        'client_id'
    ];

	
	
    public function missions()
    {
        return $this->hasMany(Mission::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'client_id');
    }

}
