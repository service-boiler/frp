<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecRelation extends Model
{

    protected $fillable = ['product_spec_id', 'product_id', 'spec_value'];

    /**
     * Товары
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    

    public function products()
	{
		return $this->hasMany(Product::class);
	}

}
