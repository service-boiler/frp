<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class EsbRequestStatus extends Model
{
    /**
     * @var string
     */
    protected $table = 'esb_request_statuses';

    /**
     * @var bool
     */
    public $timestamps = false;

}
