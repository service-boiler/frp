<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpec extends Model
{

    protected $fillable = ['name', 'name_for_site','unit','sort_order'];

    /**
     * Товары
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    

    public function products()
	{
		return $this->belongsToMany(Product::class, 'product_spec_relations');
	}

}
