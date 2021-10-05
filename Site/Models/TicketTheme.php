<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class TicketTheme extends Model
{

    /**
     * @var string
     */
    protected $table = 'ticket_themes';
    
    protected $fillable = [
        'id', 'name','default_receiver_id','default_text','for_manager','for_feedback','name_feedback'
    ];

    public $timestamps = false;
    
    public function tickets()
	{
		return $this->hasMany(Ticket::class);
	}
  
    
}
