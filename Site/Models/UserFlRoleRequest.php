<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class UserFlRoleRequest extends Model
{

	/**
	 * @var string
	 */
	protected $table = 'user_fl_role_requests';

    protected $fillable = [ 'role_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

}
