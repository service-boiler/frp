<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use ServiceBoiler\Prf\Site\Facades\Site;

class SerialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this->product->equipment);
        return [
            'serial' => $this->id,
            'product' => $this->product->name,
            'sku' => $this->product->sku,
            'model' => $this->product->equipment->name,
            'catalog' => $this->product->equipment->catalog->parentTreeName(),
        ];
    }
}