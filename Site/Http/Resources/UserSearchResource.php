<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {  
        $this->load('addresses')->load('contacts')->load('contragents')->load('parents');
        $contacts = $this->contacts()->where('type_id', 1);
        $phone=[];
        if ($contacts->exists()) {
			/** @var HasMany $phones */
            if (!empty($contacts->whereHas('phones')->first())) {
                $phones = $contacts->whereHas('phones')->first()->phones();
                if ($phones->exists()) {
                    $phone = $phones->first();
                } else {
                $phone=[];
                }
            } else {
            $phone=[];
            }
        }
        if(!empty($this->parent)) {
        $parent=$this->parent;
        } else {
        $parent=['name'=>$this->name];
        
        }
        
        return [
            'id'            => $this->id,
            'guid'          => $this->guid,
            'name'          => $this->name,
            'email'         => $this->email,
            'role'          => $this->roles->first(),
            'contacts'      => ContactResource::collection($this->whenLoaded('contacts')),
            'contragents'   => ContragentResource::collection($this->whenLoaded('contragents')),
            'parent'        => $parent,
            'phone'         => $phone,
            
        ];

    }
}