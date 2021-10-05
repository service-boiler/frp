<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRetailSaleBonus extends Model
{

    protected $fillable = [
        'product_id', 'value', 'start',  'profi',  'super',
    ];

    protected $casts = [
        'product_id' => 'string',
        'value'      => 'integer',
        'start'      => 'integer',
        'profi'      => 'integer',
        'super'      => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
