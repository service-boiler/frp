<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'type' => $this->type->name,
            'country' => $this->country->name,
            'region' => $this->region->name,
            'locality' => $this->locality,
            'name' => $this->full. ' '.$this->name,
				'image'     => $this->image()->src(),
            'geo' => $this->geo,
        ];
    }
}