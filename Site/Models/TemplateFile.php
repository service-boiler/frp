<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Contracts\SingleFileable;

class TemplateFile extends Model implements SingleFileable
{
    /**
     * @var string
     */
    protected $table = 'template_files';

    protected $fillable = [
        'name', 'file_id'
    ];

    protected $casts = [
        'name'    => 'string',
        'file_id' => 'integer',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }


    function fileStorage()
    {
        return 'templates';
    }
}
