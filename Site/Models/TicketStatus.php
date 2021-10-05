<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    /**
     * @var string
     */
    protected $table = 'ticket_statuses';

    /**
     * @var bool
     */
    public $timestamps = false;

}
