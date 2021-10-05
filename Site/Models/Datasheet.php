<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Concerns\AttachProducts;
use ServiceBoiler\Prf\Site\Contracts\SingleFileable;

class Datasheet extends Model implements SingleFileable
{

    use AttachProducts;
    /**
     * @var string
     */
    protected $table = 'datasheets';

    protected $fillable = [
        'date_from', 'date_to', 'name',
        'tags', 'file_id', 'cloud_link','active', 'type_id',
        'show_ferroli', 'show_lamborghini'
    ];

    protected $casts = [
        'name'             => 'string',
        'tags'             => 'string',
        'cloud_link'             => 'string',
        'file_id'          => 'integer',
        'type_id'          => 'integer',
        'date_from'        => 'date:Y-m-d',
        'date_to'          => 'date:Y-m-d',
        'active'          => 'boolean',
        'show_ferroli'     => 'boolean',
        'show_lamborghini' => 'boolean',
    ];

    protected $dates = [
        'date_from',
        'date_to'
    ];

    /**
     * @param $value
     */
    public function setDateFromAttribute($value)
    {
        $this->attributes['date_from'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
    }

    /**
     * @param $value
     */
    public function setDateToAttribute($value)
    {
        $this->attributes['date_to'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
    }

    /**
     * Тип документации
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(FileType::class);
    }

    /**
     * Взрывные схемы
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schemes()
    {
        return $this->hasMany(Scheme::class);
    }

    /**
     * Файл
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }

    /**
     * @return null|string
     */
    public function getDateAttribute()
    {
        $result = [];
        if ($this->getAttribute('date_from')) {
            $result[] = trans('site::messages.date_from');
            $result[] = $this->getAttribute('date_from')->format('d.m.Y');
        }
        if (!empty($result)) {
            $result[] = '•';
        }
        if ($this->getAttribute('date_to')) {
            $result[] = trans('site::messages.date_to');
            $result[] = $this->getAttribute('date_to')->format('d.m.Y');
        } else {
            $result[] = trans('site::messages.until_now');
        }

        if (!empty($result)) {
            return implode(' ', $result);
        }

        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'datasheet_product',
            'datasheet_id',
            'product_id'
        );
    }

    /**
     * @return string
     */
    function fileStorage()
    {
        return 'datasheets';
    }
}
