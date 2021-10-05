<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EquipmentSpecificationsCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $name = trans('site::eqiupment.specification_text');
        return [
            'type'     => 'FeatureCollection',
            'specifications' => EquipmentSpecificationsResource::collection($this->collection)
        ];
    }
}