<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use ServiceBoiler\Prf\Site\Facades\Site;

class ProductApi2SingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $images = [];
        $specs = [];
        foreach ($this->images()->get() as $image) {
            $images[]=$image->src();
        }
        if(!empty($this->specs->count())){
            foreach ($this->specs as $spec){
                $specs[]=['id'=>$spec->id,'spec_name'=>$spec->name_for_site,'unit'=>$spec->unit,
                    !empty($this->specRelations()->where('product_spec_id',$spec->id)->first()) ?
                        $this->specRelations()->where('product_spec_id',$spec->id)->first()->spec_value : '-'];
            }

        }

        return [
            'id'   => $this->id,
            'sku'  => $this->sku,
            'name' => $this->name,
            'equipment_id' => $this->equipment_id,
            'equipment_url' => $this->equipment_id ? route('equipments.show', $this->equipment) : '',
            'equipment_apiUrl' => $this->equipment_id ? route('api2.equipments.show', $this->equipment) : '',
            'type' => optional($this->type)->name,
            'unit' => $this->unit,
            'price' => $this->price->value,
            'price_formated' => Site::format(optional($this->price)->value),
            'currency_id' => optional($this->price)->currency_id,
            'currency_name' => optional(optional($this->price)->currency)->name,
            'currency_symbol_left' => optional(optional($this->price)->currency)->symbol_left,
            'currency_symbol_right' => optional(optional($this->price)->currency)->symbol_right,
            'url' => route('products.show', $this),
            'apiUrl' => route('api2.products.show', $this),
            'image' => $this->image()->src(),
            'images' => $images,
            'specs' => $specs,
            'details'   => !is_null($this->availableDetails) ? new ProductApi2Collection($this->availableDetails) : null,
        ];
    }
}