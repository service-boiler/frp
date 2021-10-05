<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Concerns\Sortable;


class Faq extends Model 
{

    use Sortable;

    protected $table = 'faqs';

    protected $fillable = [
        'title','body', 'url', 'sort_order'
    ];

    protected $casts = [

        'title'             => 'string',
        'url'       => 'string',
        
    ];

    

}
