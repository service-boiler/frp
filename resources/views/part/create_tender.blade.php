<div class="mb-2 list-group-item p-1 product-{{$product->id}}" data-id="{{$product->id}}">
    <div class="row">
        <div class="col-xl-12 col-sm-12">
            <dl class="dl-horizontal mt-2">
                <dt class="col-12">@lang('site::product.name') (@lang('site::product.sku'))</dt>
                <dd class="col-12">{{$product->name}} ({{$product->sku}})</dd>
               
                <dd class="col-12">
                    РРЦ:  <strong> {{ $product->retailPriceEur->currency->symbol_left }} 
                    {{$product->hasPrice ? $product->retailPriceEur->valueRaw : trans('site::price.error.price')}}</strong>
                    <input type="hidden" id="retailprice-{{$product->id}}" value="{{$product->hasPrice ? $product->retailPriceEur->valueRaw : trans('site::price.error.price')}}">
                </dd>
                
                <dt class="col-12"></dt>
                <dd class="col-12">
                    <div class="row">
                        <div class="col-4">
                            <label class="control-label"
                                       for="date_trade">Количество</label>
                            <input type="number" name="count[{{$product->id}}][count]"
                                required
                                data-cost="{{ $product->hasPrice ? $product->retailPriceEur->valueBack : 0}}"
                                title="@lang('site::part.count')"
                                class="form-control parts_count" value="{{!empty($count) ? $count : 20}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="control-label" for="date_trade">Скидка от РРЦ для дистр-а</label>
                        </div>
                        <div class="col-6">
                                <label class="control-label" for="date_trade">Скидка от РРЦ для объекта</label>
                       </div>
                    </div>
                        
                        
                        <div class="row">
                        <div class="col-3">
                            
                            
                            <input type="number" step="0.10" name="count[{{$product->id}}][discount]" id="discount-{{$product->id}}"
                                    data-product="{{$product->id}}" data-price-type="pricedistr-{{$product->id}}"
                                    title="Скидка"
                                    class="form-control parts_count" value="{{!empty($discount) ? $discount : 30}}">
                                    
                        </div>
                        <div class="col-3 mt-2 font-weight-bold" id="pricedistr-{{$product->id}}">
                            (€ {{round($product->hasPrice ? $product->retailPriceEur->valueRaw*(100-(!empty($discount) ? $discount : 30))/100 : 0, 0)}} )</div>
                        <div class="col-3">
                            
                            
                            <input type="number" step="0.10" name="count[{{$product->id}}][discount_object]" id="discount-{{$product->id}}"
                                    data-product="{{$product->id}}" data-price-type="priceobject-{{$product->id}}"
                                    title="Скидка"
                                    class="form-control parts_count" value="{{!empty($discount_object) ? $discount_object : 25}}">
                                    
                        </div>
                        <div class="col-3 mt-2 font-weight-bold" id="priceobject-{{$product->id}}">
                            (€ {{round($product->hasPrice ? $product->retailPriceEur->valueRaw*(100-(!empty($discount_object) ? $discount_object : 25))/100 : 0, 0)}} )</div>
                    </div>
                    
                </dd>     
                
            </dl>
        </div>
        <div class="col-xl-4 col-sm-12">
            <dl class="dl-horizontal mt-2">
                <dt class="col-12"></dt>
                <dd class="col-12">
                    <button type="button" class="btn btn-danger btn-sm part-delete" data-id="{{$product->id}}"
                            data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i> @lang('site::messages.delete')</span>
                    </button>
                </dd>
            </dl>
        </div>
    </div>
</div>
