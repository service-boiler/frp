<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MissionStatus extends Model
{

	/**
	 * @var string
	 */
	protected $table = 'mission_statuses';
    
    public $timestamps = FALSE;
    /**
     * @var array
     */
    protected $fillable = [
        'name', 
        'button', 
        'icon', 
        'color', 
        'sort_order', 
    ];

	/**
	 * @var array
	 */
    protected $casts = [
        'name'            => 'string',
    ];

	
    public function missions()
    {
        return $this->hasMany(Mission::class);
    }

}
