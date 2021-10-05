<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CatalogTreeCollection extends ResourceCollection
{


    public function toArray($request)
    {

        return CatalogTreeResource::collection($this->collection);
    }
}