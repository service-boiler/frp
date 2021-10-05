<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Facades\Site;

class Price extends Model
{

    /**
     * Currency
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(PriceType::class);
    }

//    public function format()
//    {
//        $result = [];
//        if (Site::currency()->symbol_left != '') {
//            $result[] = Site::currency()->symbol_left;
//        }
//        $result[] = number_format($this->price(), config('site.decimals', 0), config('site.decimalPoint', '.'), config('site.thousandSeparator', ' '));
//        if (Site::currency()->symbol_right != '') {
//            $result[] = Site::currency()->symbol_right;
//        }
//
//        return implode(' ', $result);
//    }

//    public function price()
//    {
//
//        $price = $this->getAttribute('price') * Site::currencyRates(
//                $this->currency,
//                Site::currency()
//            );
//
//        if (($round = config('site.round', false)) !== false) {
//            $price = round($price, $round);
//        }
//
//        if (($round_up = config('site.round_up', false)) !== false) {
//            $price = ceil($price / (int)$round_up) * (int)$round_up;
//        }
//
//        return $price;
//    }

    /**
     * Получит цену товара в валюте текущего пользователя
     * @return float|mixed
     */
    public function getValueAttribute()
    {
        if (!$this->exists) {
            return 0;
        }
        $price = $this->getAttribute('price') * Site::currencyRates($this->type->currency, Site::currency());

        if (($round_value = config('site.round', false)) !== false) {
            $price = round($price, $round_value);
        }

        if (($round_up_value = config('site.round_up', false)) !== false) {
            $price = ceil($price / (int)$round_up_value) * (int)$round_up_value;
        }

        return $price;
    }
    public function getValueRawAttribute()
    {
        if (!$this->exists) {
            return 0;
        }
        $price = $this->getAttribute('price');

            $price = round($price, '2');

        return $price;
    }
    public function getValueEurAttribute()
    {
        if (!$this->exists) {
            return 0;
        }
        $currencyEur=Currency::find(978)->rates;
        
        if($this->currency->id != '978') {
            $price = $this->getAttribute('price')/$currencyEur;
        } else {
            $price = $this->getAttribute('price');
        }
        
        $price = round($price, '2');

        return $price;
    }

    public function getValueBackAttribute()
    {
        if (!$this->exists) {
            return 0;
        }
        $price = $this->getAttribute('price') * Site::currencyRates($this->type->currency, Site::currency());

        if (($round_value = config('site.round_back', false)) !== false) {
            $price = round($price, $round_value);
        }

        if (($round_up_value = config('site.round_up', false)) !== false) {
            $price = ceil($price / (int)$round_up_value) * (int)$round_up_value;
        }

        return $price;
    }

    /**
     * Scope a query to only enabled countries.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTypeEnabled($query)
    {
        return $query->whereHas('type', function ($type) {
            /** @var \Illuminate\Database\Eloquent\Builder $type */
            $type->where('enabled', 1);
        });
    }

}
