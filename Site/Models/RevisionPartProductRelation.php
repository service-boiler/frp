<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Facades\Site;

class RevisionPartProductRelation extends Model
{

    protected $fillable = [
        'revision_product_id', 'revision_part_id', 'start_serial'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    

}
