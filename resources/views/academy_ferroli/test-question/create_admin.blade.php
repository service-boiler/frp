
<div class="list-group-item p-1 product-{{$product->id}}" data-id="{{$product->id}}">
    <div class="row">
        <div class="col-xl-7 col-sm-12">
            <dl class="mt-2">
                
                <dd class="col-12">{{$product->name}} ({{$product->sku}})</dd>
                
            </dl>
        </div>
        <div class="col-xl-5 col-sm-12">
            <dl class="row mt-2">
             
                <dd class="col-3">
                    <select name="count[{{$product->id}}]"
                            required
                            data-cost="{{ $product->hasPrice ? $product->standOrderPrice->value : 0}}"
                            title="@lang('site::part.count')"
                            class="form-control parts_count">
                        @foreach(range(1,4,1) as $range_count)
                            <option @if(old('count.'.$product->id, (isset($count) ? $count : null)) == $range_count) selected
                                    @endif value="{{ $range_count }}">{{ $range_count }}</option>
                        @endforeach
                    </select>
                </dd>
                
                <dd class="col-3">
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
