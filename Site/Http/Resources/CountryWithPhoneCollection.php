<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CountryWithPhoneCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => CountryWithPhoneResource::collection($this->collection),
        ];
    }
}