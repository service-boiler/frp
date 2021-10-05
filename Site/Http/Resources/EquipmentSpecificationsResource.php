<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use ServiceBoiler\Rbac\Models\Equipment;
use ServiceBoiler\Rbac\Models\Role;

class EquipmentSpecificationsResource extends JsonResource
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
            'id'         => $this->id,
            'properties' => [
                'SpecificationsList' => view('site::equipment.specifications', ['equipment_id' => $this->id])
                ->render()
                
            ],
            
        ];
    }
}
