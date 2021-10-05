<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use ServiceBoiler\Prf\Site\Facades\Site;
use ServiceBoiler\Prf\Site\Models\Equipment;

class EquipmentResource extends JsonResource
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
            'id'        => $this->id,
            'name'      => $this->name,
            'route' => route('equipments.show', $this),
            'image'     => $this->image()->src(),
            'catalog'   => !is_null($this->catalog_id) ? new CatalogResource($this->catalog) : null
        ];
    }
}