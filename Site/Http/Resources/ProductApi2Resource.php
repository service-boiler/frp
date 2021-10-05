<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use ServiceBoiler\Prf\Site\Facades\Site;

class ProductApi2Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            'sku'  => $this->sku,
            'old_sku'  => $this->old_sku,
            'name' => $this->name,
            'type' => optional($this->type)->name,
            'type_id' => $this->type_id,
            'equipment_id' => $this->equipment_id,
            'unit' => $this->unit,
            'url' => route('products.show', $this),
            'apiUrl' => route('api2.products.show', $this),
            'image' => $this->image()->src(),

        ];
    }
}