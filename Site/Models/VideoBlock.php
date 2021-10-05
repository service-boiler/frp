<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Concerns\Sortable;


class VideoBlock extends Model 
{

    use Sortable;

    protected $table = 'video_blocks';

    protected $fillable = [
        'title', 'url', 'sort_order','show_ferroli','show_market_ru'
    ];

    protected $casts = [

        'title'             => 'string',
        'url'       => 'string',
        'sort_order'       => 'integer',
        'show_ferroli'     => 'boolean',
        'show_market_ru'   => 'boolean',
    ];

    

}
