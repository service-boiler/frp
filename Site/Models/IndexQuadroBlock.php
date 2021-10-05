<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Concerns\Sortable;
use ServiceBoiler\Prf\Site\Contracts\SingleImageable;


class IndexQuadroBlock extends Model implements SingleImageable
{

    use Sortable;

    protected $table = 'index_quadro_blocks';

    protected $fillable = [
        'title', 'url', 'image_id', 'text', 'text_hover', 'sort_order','enabled'
    ];

    protected $casts = [

        'title'             => 'string',
        'url'       => 'string',
        'text'       => 'string',
        'text_hover'       => 'string',
        'sort_order'       => 'integer',
        'enabled'     => 'boolean'
    ];

    public function image()
    {
        return $this->belongsTo(Image::class)->withDefault([
            'storage' => 'index_quadro',
            'path'    => 'noimage.png',
        ]);
    }
    
    function imageStorage()
    {
        return 'index_quadro';
    }

}
