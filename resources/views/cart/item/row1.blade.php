<tr class="cart-item" id="cart-item-{{ $item->product_id }}">
    <td class="text-left">
        <div class="row">
            <div class="col item-img d-none d-xl-block">
                @if($item->hasImage())
                    <img class="img-fluid border" src="{{ $item->image }}">
                @endif
            </div>
            <div class="col item-info">
                @if($item->hasType())
                    <small class="d-block text-muted">{{ $item->type }}</small>
                @endif
                <a href="{{ $item->url() }}">
                    <span class="item-name">{!! htmlspecialchars_decode($item->name) !!}</span>
                    @if($item->hasSku())
                        <span class="item-sku">({{ $item->sku }})</span>
                    @endif
                </a>
                <div>
                    @if($item->availability)
                        <span class="item-availability badge badge-success">@lang('site::cart.in_stock')</span>
                    @else
                        <span class="item-availability badge badge-light">@lang('site::cart.out_of_stock')</span>
                    @endif
                </div>
                <a class="text-danger d-block btn-row-delete mt-3"
                   data-form="#cart-item-delete-form-{{$item->id}}"
                   data-btn-delete="@lang('site::messages.delete')"
                   data-btn-cancel="@lang('site::messages.cancel')"
                   data-label="@lang('site::messages.delete_confirm')"
                   data-message="@lang('site::messages.delete_sure') {{ $item->name }}?"
                   data-toggle="modal" data-target="#form-modal"
                   href="javascript:void(0);" title="@lang('site::messages.delete')"><i
                            class="fa fa-close"></i> @lang('site::cart.item_delete')
                </a>
                <form id="cart-item-delete-form-{{$item->id}}"
                      action="{{route('removeCartItem')}}"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                </form>
            </div>
        </div>
    </td>
    <td class="text-center item-qty">
        <form action="{{route('updateCart')}}" method="post">
            @csrf
            @method('put')
            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
            <button class="btn btn-light qty-btn btn-minus" @if($item->quantity() == 1) disabled @endif><i
                        class="fa fa-chevron-down"></i></button>
            <input data-toggle="popover" data-placement="top" data-trigger="focus"
                   title="@lang('site::cart.item_hint_title')" name="quantity"
                   data-content="@lang('site::cart.item_hint_content', ['max' => config('cart.item_max_quantity')])"
                   min="1" max="{{ config('cart.item_max_quantity') }}"
                   pattern="([1-9])|([1-9][0-9])" type="number" value="{{ $item->quantity() }}" required/>
            <button class="btn btn-light qty-btn btn-plus"
                    @if($item->quantity() == config('cart.item_max_quantity')) disabled @endif>
                <i class="fa fa-chevron-up"></i>
            </button>
        </form>
        @if($item->hasUnit())
            <small class="item-unit badge badge-light">{{ $item->unit }}</small>
        @endif
    </td>
    <td class="text-right item-price d-none d-xl-table-cell d-md-table-cell">
        {{ Cart::price_format($item->price) }}

    </td>
    <td class="text-right item-subtotal">
        {{ Cart::price_format($item->subtotal()) }}
    </td>
</tr>