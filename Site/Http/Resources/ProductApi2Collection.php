<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductApi2Collection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function toArray($request)
    {
        if($this->collection->count()==1){
            return ProductApi2SingleResource::collection($this->collection);
        } else {
            return ProductApi2Resource::collection($this->collection);
        }
    }
}