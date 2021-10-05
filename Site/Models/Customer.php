<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

use ServiceBoiler\Prf\Site\Concerns\AttachCustomerRoles;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Customer extends Model
{
    use AttachCustomerRoles;
	/**
	 * @var string
	 */
	protected $table = 'customers';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'comment','email','phone','any_contacts','region_id','locality',
    ];

    protected $casts = [
        'name'       => 'string',
        'comment'    => 'string',
        'email'    => 'string',
        'phone'    => 'string',
        'region_id'    => 'string',
        'locality'    => 'string',
    ];

    public function customerRoles()
    {
        return $this->belongsToMany(
            CustomerRole::class,
            'customer_role_relations',
            'customer_id',
            'customer_role_id'
        );
    }
    public function roles()
    {
        return $this->belongsToMany(
            CustomerRole::class,
            'customer_role_relations',
            'customer_id',
            'customer_role_id'
        );
    }
    public function contacts()
	{
		return $this->hasMany(CustomerContact::class);
	}
    
    public function region()
	{
		return $this->belongsTo(Region::class);
	}
    
    public function TenderContact($value)
    {
        return $this->contacts()->find($value);
    }
    
    public function getPhoneAttribute($value)
    {
        return $value ? preg_replace(config('site.phone.get.pattern'), config('site.phone.get.replacement'), $value) : null;
    }

    /**
     * @param $value
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = $value ? preg_replace(config('site.phone.set.pattern'), config('site.phone.set.replacement'), $value) : null;
    }

    /**
     * Пользователь
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
