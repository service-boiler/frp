<div class="list-group-item p-1 product-{{$product->id}}" data-id="{{$product->id}}">
    <div class="row">
        <div class="col-xl-6 col-sm-12">
            <dl class="dl-horizontal mt-2">
                <dt class="col-12">@lang('site::product.name') (@lang('site::product.sku'))</dt>
                <dd class="col-12">{{$product->name}} ({{$product->sku}})</dd>
                <dt class="col-12">@lang('site::part.cost') вход</dt>
                <dd class="col-12">
                    
                    {{$product->hasPrice ? number_format($product->repairPrice->valueBack*$repair_price_ratio, 2, '.', ' ') : trans('site::price.error.price')}}
                    
                </dd>
            </dl>
        </div>
        <div class="col-xl-3 col-sm-6">
            <dl class="dl-horizontal mt-2">
                <dt class="col-12">@lang('site::part.count')</dt>
                <dd class="col-12">
                    <select name="count[{{$product->id}}][count]"
                            required
                            data-cost="{{ $product->hasPrice ? $product->repairPrice->valueBack*$repair_price_ratio : 0}}"
                            title="@lang('site::part.count')"
                            class="form-control parts_count">
                        @foreach(range(1,4,1) as $range_count)
                            <option @if(old('count.'.$product->id, (isset($count) ? $count : null)) == $range_count) selected
                                    @endif value="{{ $range_count }}">{{ $range_count }}</option>
                        @endforeach
                    </select>
                </dd>
                <dt class="col-12"></dt>
                
            </dl>
        </div>
        <div class="col-xl-3 col-sm-6">
            <dl class="dl-horizontal mt-2">
                <dt class="col-12">@lang('site::user.esb_repair.price')</dt>
                <dd class="col-12">
                    <input  class="form-control" type="number" name="count[{{$product->id}}][cost]" 
                        value="{{ !empty($cost) ? $cost : ($product->hasPrice ? round($product->retailPrice->valueBack) : 0)}}">
                </dd>
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
