<?php

namespace ServiceBoiler\Prf\Site\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $this->load('items');

        $order=[
            'id'         => $this->id,
            'guid'       => $this->guid,
            'currency_id'  => !empty($this->tender2) ? '978' : 643,
            'tender' => !empty($this->tender) ? $this->tender->id : 0,
            'user'       => [
                'guid'         => $this->user->guid,
                'currency_id'  => $this->user->currency_id,
                'warehouse_id' => $this->user->warehouse_id,
            ],
            'items'      => OrderItemResource::collection($this->items),
            'contragent' => new ContragentResource($this->contragent),
        ];
        if(!empty($this->tender)){
            $items='';
            foreach($this->tender->tenderProducts as $item){
                $items=$items .$item->product->name .' по €' .round($item->product->retailPriceEur->valueRaw) .' (-'. $item->discount ."%) \n ";
            }
            
        
            $order = array_merge($order,
            ['tender_comment' => 'Тендер №' .$this->tender->id .' ' .$this->tender->address. "  \n " .$items .' \n Курс: '.$this->tender->rates .' Цена до:' .$this->tender->date_price->format('d.m.Y')]
            );
        }
        
        return $order;

    }
}