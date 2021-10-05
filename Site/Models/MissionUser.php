<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MissionUser extends Model
{

	/**
	 * @var string
	 */
	protected $table = 'mission_users';
    
    public $timestamps = FALSE;
    /**
     * @var array
     */
    protected $fillable = [
        'mission_id', 
        'user_id', 
        'main', 
    ];

	
	
    public function missions()
    {
        return $this->hasMany(Mission::class);
    }

}
