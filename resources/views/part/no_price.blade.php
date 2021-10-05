<div class="mb-2 list-group-item p-1 product-{{$product->id}}" data-id="{{$product->id}}">
    <div class="row">
        <div class="col-xl-12 col-sm-12">
            <dl class="dl-horizontal mt-2">
                <dt class="col-12">@lang('site::product.name') (@lang('site::product.sku'))</dt>
                <dd class="col-12">{{$product->name}} ({{$product->sku}})</dd>
                <dd class="col-12"><b>Цена не установлена. Товар не будет привязан к тендеру.</b></dd>
                
               
                
                
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
