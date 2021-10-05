<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReceiverGroup extends Model
{

    /**
     * @var string
     */
    protected $table = 'ticket_receiver_groups';
    
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
    
    public function tickets()
	{
		return $this->hasMany(Ticket::class);
	}
  
    
}
