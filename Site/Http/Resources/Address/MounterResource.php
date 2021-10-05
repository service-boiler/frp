<?php

namespace ServiceBoiler\Prf\Site\Http\Resources\Address;

use Illuminate\Http\Resources\Json\JsonResource;
use ServiceBoiler\Rbac\Models\Role;

class MounterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $icon = 'islands#circleDotIcon';

        return [
            'type'       => 'Feature',
            'id'         => $this->id,
            'geometry'   => [
                'type'        => 'Point',
                'coordinates' => [$this->lat(), $this->lon()]
            ],
            'properties' => [
                'balloonContentBody' => view('site::map.balloon.mounter_request', [
                    'id'      => $this->id,
                    'name'      => $this->name,
                    'logo'      => $this->addressable->logo,
						  'image'      => $this->images->sortBy('sort_order')->first(),
						  'images'      => $this->images,
                    'web'      => $this->web,
                    'phones'     => $this->phones,
                    'email'   => $this->email,
                    'description'   => $this->description,
                    'is_shop' => $this->is_shop,
                    'is_service' => $this->is_service,
                    'address' => $this->full,
                    'address_id' => $this->id,
                    'accepts' => $this->addressable->authorization_accepts()->where('role_id', 11)->whereHas('type', function($query){
                        $query->where('brand_id', config('site.brand_default'));
                    })->get()
                ])->render(),
                'balloonContentBodyList' => view('site::map.balloon.mounter_request_list', [
                    'name'      => $this->name,
                    'logo'      => $this->addressable->logo,
						  'image'      => $this->images->sortBy('sort_order')->first(),
						  'images'      => $this->images,
                    'web'      => $this->web,
                    'phones'     => $this->phones,
                    'email'   => $this->email,
                    'description'   => $this->description,
                    'is_shop' => $this->is_shop,
                    'is_service' => $this->is_service,
                    'address' => $this->full,
                    'address_id' => $this->id,
                    'accepts' => $this->addressable->authorization_accepts()->where('role_id', 11)->whereHas('type', function($query){
                        $query->where('brand_id', config('site.brand_default'));
                    })->get()
                ])->render(),
                'balloonMaxWidth'    => 700
            ],
            'options'    => ['preset' => $icon, 'zIndex' => 10],
        ];
    }
}
