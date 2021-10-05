<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Facades\Site;

class TenderProduct extends Model
{

    protected $fillable = [
        'product_id', 'count', 'cost', 'discount_object', 'discount','currency','approved_status'
    ];
    protected $casts = [

		'count'    =>'integer',
        'discount_object'       =>'decimal:2',
        'discount'     => 'decimal:2',
		
	
	];
    /**
     * Товар
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Отчет по ремониу
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    /**
     * Стоимость детали ИТОГО
     *
     * @return float
     */
    public function getTotalAttribute()
    {
        return $this->cost() * $this->count;
    }

    /**
     * Стоимость детали
     *
     * @return float
     */
    public function cost()
    {
        return $this->cost;
//        switch ($this->repair->getAttribute('status_id')) {
//            case 5:
//            case 6:
//                return $this->cost;
//            default:
//                return $this->price * $this->rates;
//        }
    }

    public function setApprovedStatus($status_id) {
        $this->update(['approved_status' => $status_id]);
    }
    
    public function setDiscoutAttribute($value) {
        $this->attributes['discount'] = number_format($value, 2);
    }
    
    public function getTotalEuroAttribute()
    {

        $cacheKey = 'currency_978_' . $this->repair->getAttribute('date_repair');

        return cache()->remember($cacheKey, config('site::cache.ttl'), function () {
            $rates = CurrencyArchive::query()
                ->select(array('rates'))
                ->where('currency_id', 978)
                ->where('date', $this->repair->getAttribute('date_repair'))
                ->firstOrNew(['rates' => Currency::query()->find(978)->getAttribute('rates')])
                ->getAttribute('rates');

            return $this->cost / $rates * $this->count;
        });
    }

    
   
    public function getPriceDistrEuroAttribute()
    {
        if($this->approved_status == '1') {
            return $this->cost;
            
        } else { 
            return round($this->product->retailPriceEur->valueRaw*(100 - $this->discount)/100,0);
        }
        
   }
    public function getPriceObjectEuroAttribute()
    {
            return round($this->product->retailPriceEur->valueRaw*(100 - $this->discount_object)/100,0);
        
   }
    public function getProfitEuroAttribute()
    {
            return round(($this->price_object_euro*$this->tender->rates_object - $this->price_distr_euro*$this->tender->rates)/$this->tender->rates,0);
    }

    /**
     * Узнать, имеет ли деталь цену
     *
     * @return bool
     */
    public function hasPrice()
    {
        return (float)$this->price > 0.00;
    }

}
