<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Rbac\Models\Role;
use ServiceBoiler\Prf\Site\Concerns\AttachAuthorizationTypes;

class AuthorizationAccept extends Model
{

    use AttachAuthorizationTypes;

    /**
     * @var string
     */
    protected $table = 'authorization_accepts';

    protected $fillable = [
        'role_id', 'user_id', 'type_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(AuthorizationType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

}
