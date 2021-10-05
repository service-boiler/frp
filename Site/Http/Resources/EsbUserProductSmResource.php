<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use ServiceBoiler\Prf\Site\Facades\Site;

class EsbUserProductSmResource extends JsonResource
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
            'id'   => $this->id,
            'product_name' =>$this->product ? $this->product->name : $this->product_no_cat,
            'serial' =>$this->serial,
            'date_sale' =>$this->date_sale ? $this->date_sale->format('d.m.Y') : null,
            'sale_comment' => $this->sale_comment,
            'user_name' => $this->esbUser->first_name .' ' .mb_substr($this->esbUser->last_name,0,1),
            'address_str' => $this->address->locality .', ' .$this->address->street,
            'date_launch' => $this->launches()->exists() ? $this->launches()->orderByDesc('created_at')->first()->date_launch->format('d.m.Y') : null,
            'product_no_cat' => $this->product_no_cat,
            'permission_ok' => 0,
            
            
        ];
    }
}