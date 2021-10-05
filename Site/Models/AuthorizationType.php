<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorizationType extends Model
{
    /**
     * @var string
     */
    protected $table = 'authorization_types';

    /**
     * @var array
     */
    protected $fillable = ['name', 'brand_id', 'enabled'];

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }


    /**
     * Авторизации
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function authorizations()
    {

        return $this->belongsToMany(
            Authorization::class,
            'authorization_type',
            'type_id',
            'authorization_id'
        );
    }

    /**
     * Бренд
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(AuthorizationBrand::class);
    }

}
