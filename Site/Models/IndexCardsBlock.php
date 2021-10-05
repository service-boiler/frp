<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Concerns\Sortable;
use ServiceBoiler\Prf\Site\Contracts\SingleImageable;


class IndexCardsBlock extends Model implements SingleImageable
{

    use Sortable;

    protected $table = 'index_cards_blocks';

    protected $fillable = [
        'title', 'url', 'image_id', 'text', 'sort_order','show_ferroli','show_market_ru'
    ];

    protected $casts = [

        'title'             => 'string',
        'url'       => 'string',
        'sort_order'       => 'integer',
        'show_ferroli'     => 'boolean',
        'show_market_ru'   => 'boolean',
    ];

    public function image()
    {
        return $this->belongsTo(Image::class)->withDefault([
            'storage' => 'index_cards',
            'path'    => 'noimage.png',
        ]);
    }
    
    function imageStorage()
    {
        return 'index_cards';
    }

}
