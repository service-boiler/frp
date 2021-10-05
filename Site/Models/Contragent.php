<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Contracts\Addressable;

class Contragent extends Model implements Addressable
{
    protected $fillable = [
        'type_id', 'name','name_short', 'nds', 'nds_act', 'inn', 'ogrn',
        'okpo', 'kpp', 'rs', 'ks', 'bik', 'bank',
        'organization_id', 'contract'
    ];

    /**
     * @var string
     */
    protected $table = 'contragents';

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->organization_id = config('site.defaults.user.organization_id');
        });
    }

    public function getShortNameAttribute(){
        return $this->name_short ? $this->name_short : $this->name;
    }


    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'schedulable');
    }

    public function hasOrganization()
    {
        return !is_null($this->getAttribute('organization_id'));
    }

    /**
     * Тип контрагента
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(ContragentType::class);
    }

    /**
     * @return bool
     */
    public function check()
    {
        return mb_strlen($this->getAttribute('name'), 'UTF-8') > 0
            && (
                $this->getAttribute('type_id') == 1
                && strlen($this->getAttribute('inn')) == 10
                || strlen($this->getAttribute('inn')) == 12
            )
            && in_array(strlen($this->getAttribute('ogrn')), [13, 15])
            && in_array(strlen($this->getAttribute('okpo')), [8, 10])
            && (
                $this->getAttribute('type_id') == 2 || strlen($this->getAttribute('kpp')) == 9
            )
            && strlen($this->getAttribute('rs')) == 20
            && in_array(strlen($this->getAttribute('bik')), [9, 11])
            && mb_strlen($this->getAttribute('bank'), 'UTF-8') > 0
            && in_array(strlen($this->getAttribute('ks')), [0, 20])
            && !is_null($this->getAttribute('organization_id'))//&& mb_strlen($this->getAttribute('contract'), 'UTF-8') > 0
            ;
    }

    /**
     * Заказы
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Акты выполненных работ
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function acts()
    {
        return $this->hasMany(Act::class);
    }

    /**
     * Заказы
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }

    public function toArray()
    {
        return [
            'our'       => 0,
            'guid'      => $this->getAttribute('guid'),
            'name'      => $this->getAttribute('name'),
            'inn'       => $this->getAttribute('inn'),
            'okpo'      => $this->getAttribute('okpo'),
            'rs'        => $this->getAttribute('rs'),
            'ks'        => $this->getAttribute('ks'),
            'bik'       => $this->getAttribute('bik'),
            'bank'      => $this->getAttribute('bank'),
            'address'   => $this->addresses()->where('type_id', 1)->first()->full,
            'contract'  => $this->getAttribute('contract'),
            'nds_value' => config('site.nds', 18),
            'nds'       => $this->getAttribute('nds'),
            'nds_act'   => $this->getAttribute('nds_act'),
        ];
    }


    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function addressLegal()
    {
        return $this->addresses()->where('type_id',1)->first();
    }

    /**
     * Клиент
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Организация
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class)->withDefault([
            'name' => trans('site::messages.not_indicated_f'),
        ]);
    }

    /**
     * Договора
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    function path()
    {
        return 'contragents';
    }

    function lang()
    {
        return 'contragent';
    }
}
