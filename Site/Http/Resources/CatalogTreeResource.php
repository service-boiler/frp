<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CatalogTreeResource extends JsonResource
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
            'name'      => $this->parentTreeName(),
            'name_plural'      => $this->name_plural,
            'name_for_menu'      => $this->name_for_menu,
            'parent_catalog_id'      => $this->catalog_id,
            'image'      => $this->image->src(),
            'url' => route('catalogs.show', $this),
            'apiUrl' => route('api2.catalogs.show', $this),
            'childs'   => !is_null($this->catalogs) ? new CatalogTreeCollection($this->catalogs) : null,
            'equipments'   => !is_null($this->equipments) ? new EquipmentCollection($this->equipments) : null,
        ];
    }
}