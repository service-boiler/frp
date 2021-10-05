<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Contracts\SingleFileable;

class EsbContractTemplate extends Model implements SingleFileable
{
    /**
     * @var string
     */
    protected $table = 'esb_contract_templates';

    protected $fillable = [
        'name', 'prefix', 'file_id', 'enabled','shared'
    ];

    protected $casts = [
        'name'    => 'string',
        'prefix'  => 'string',
        'enabled'  => 'boolean',
        'shared'  => 'boolean',
        'file_id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function esbContracts()
    {
        return $this->hasMany(EsbContract::class, 'template_id');
    }
    public function esbContractTypes()
    {
        return $this->hasMany(EsbContractType::class, 'template_id');
    }
    public function presetFields()
    {
        return EsbContractField::where('preset',1);
    }
    public function esbContractFields()
    {
        return $this->hasMany(EsbContractField::class, 'esb_contract_template_id');
    }
    public function esbContractNonCalcFields()
    {
        return EsbContractField::query()->whereIn('id',array_merge($this->esbContractFields->pluck('id')->toArray(),$this->presetFields()->whereNull('calculated')->pluck('id')->toArray()))->get();
    }

    public function esbUser()
    {
        return $this->hasMany(EsbUser::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }

    /**
     * Scope a query to only enabled countries.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCanCreate($query)
    {
        return $query->where('active', 1)
            ->whereDoesntHave('esbContractTypes', function ($contractType) {
                $contractType->whereHas('contract', function ($contract) {
                    $contract->where('user_id', auth()->user()->getAuthIdentifier());
                });
            });
    }

    /**
     * @return string
     */
    function fileStorage()
    {
        return 'esb_contract_templates';
    }
}
