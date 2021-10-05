<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {  
        
        $contact=[];
        if($this->contacts()->exists()) {
        $tmp_contact = $this->contacts()->orderByDesc('created_at')->first();
      
        $contact = ['name'=>$tmp_contact->name, 
                    'id'=>$tmp_contact->id, 
                    'email'=>$tmp_contact->email, 
                    'phone'=>$tmp_contact->phone, 
                    'lpr'=>$tmp_contact->lpr, 
                    'position'=>$tmp_contact->position];
        }
        
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'region_id'          => $this->region ? $this->region->id : null,
            'region'          => $this->region ? $this->region->name : null,
            'locality'          => $this->locality,
            'phone'          => $this->phone,
            'email'          => $this->email,
            'contact'          => $contact,
            
            
        ];

    }
}