@extends('site::cart.buy.button')
@section('button')
    <div class="input-group">
        <input name="quantity" type="hidden" value="1">
        <div class="input-group-append">
            <button type="submit" class="add-to-cart btn btn-ms btn-lp-buy">
                <span class="d-sm-inline-block">@lang('site::cart.to_order')</span>
            </button>
        </div>
    </div>
@endsection