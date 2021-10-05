<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserRegisterCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {   return parent::toArray($request);
        
       /* $this->collection->each(function ($item, $key) {
//        dd($key->name);
        });
        
        return 
            parent::toArray($this->collection)
            
        ;
       /* 
        //

//        return [
//            'data' => $this->collection,
//            'links' => [
//                'self' => 'link-value',
//            ],
//        ];
        return [
            'id' => $this->id,
            'number' => $this->number,
            'files' => ContragentResource::collection($this->files),
        ];*/
    }
}