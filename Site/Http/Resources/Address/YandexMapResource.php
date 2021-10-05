<?php

namespace ServiceBoiler\Prf\Site\Http\Resources\Address;

use Illuminate\Http\Resources\Json\JsonResource;
use ServiceBoiler\Rbac\Models\Role;

class YandexMapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $icon = $request->route()->getName() == 'api.dealers.index' ? 'islands#orangeShoppingIcon' : 'islands#orangeRepairShopIcon';
        $roles = [];
        foreach (Role::query()->where('display', 1)->get() as $role){
            if($this->addressable->hasRole($role->name)){
                $roles[] = $role->title;
            }
        }
        return [
            'type'       => 'Feature',
            'id'         => $this->id,
            'geometry'   => [
                'type'        => 'Point',
                'coordinates' => [$this->lat(), $this->lon()]
            ],
            'properties' => [
                'balloonContentBody' => view('site::address.balloon', [
                    'name'      => $this->name,
                    'roles'      => $roles,
                    'web'      => $this->addressable->web,
                    'phones'     => $this->phones,
                    'email'   => $this->email,
                    'description'   => $this->description,
                    'address' => $this->full,
                    'is_shop' => $this->is_shop,
                    'is_eshop' => $this->is_eshop,
                    'is_service' => $this->is_service,
                ])->render(),
                'balloonMaxWidth'    => 700
            ],
            'options'    => ['preset' => $icon, 'zIndex' => 10],
        ];
    }
}
