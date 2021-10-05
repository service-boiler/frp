<?php
namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerContact extends Model
{
    protected $fillable = [
        'name', 'position','email','phone','any_contacts','lpr','customer_id'
    ];

    /**
     * @var string
     */
    protected $table = 'customer_contacts';

    /**
     * Клиент
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
