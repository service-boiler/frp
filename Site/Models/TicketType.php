<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    /**
     * @var string
     */
    protected $table = 'ticket_types';
    protected $fillable = [
		'id','name'
	];


    /**
     * @var bool
     */
    public $timestamps = false;

}
