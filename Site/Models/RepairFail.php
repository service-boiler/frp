<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class RepairFail extends Model
{

	/**
	 * @var string
	 */
	protected $table = 'repair_fails';

    protected $fillable = [
        'field', 'comment'
    ];

}
