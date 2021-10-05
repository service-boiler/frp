@extends('layouts.app')
@push('styles')
<style type="text/css">
    #product-row .product-col {
        border: 1px solid #f9f9f9;
    }

    #product-row .product-col:hover {
        border: 1px solid #FFECB3;
        background-color: #fcf3d8;
    }
</style>
@endpush
@section('title')@lang('site::product.products') @lang('site::product.view.list')@lang('site::messages.title_separator')@endsection
@section('header')
    @include('site::header.front',[
        'h1' => __('site::product.products'). ' '. __('site::product.view.list'),
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['name' => __('site::product.products'). ' '. __('site::product.view.list')]
        ]
    ])
@endsection
@section('content')
    <div class="container">
        @can('product_list', Auth::user())
            <div class=" border p-3 mb-2">
                <a href="{{route('products.index')}}" class="d-block d-sm-inline btn btn-ms">
                    <i class="fa fa-@lang('site::product.icon')"></i> @lang('site::messages.show') @lang('site::product.view.shop')
                </a>
            <button form="repository-form"
                    type="submit"
                    name="excel"
                    class="d-block d-sm-inline-block ml-2 btn btn-primary p-1">
                <i class="fa fa-upload"></i>
                <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
            </button>
            <div class="ml-3 d-inline-block font-weight-bold">
            Внутренний курс Евро установлен {{$static_currency->value}} &#8381; / &#8364;
            </div>
            </div>
            
        @endcan
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $products])@endpagination
        {{$products->render()}}
        <div class="row mt-2 mb-4" id="product-row">
            @foreach($products as $key => $product)
                <div class="col-12 product-col">
                    @can('buy', $product)
                        <div class="d-inline-block mr-2">
                            @include('site::cart.buy.small', $product->toCart())
                        </div>
                    @endcan
                    <span class="width-20 mr-1">
                        <i data-toggle="tooltip"
                           data-placement="top"
                           title="@if($product->quantity > 0) @lang('site::product.in_stock') @else @lang('site::product.not_available') @endif"
                           class="fa fa-circle text-@if($product->quantity > 0) text-success @else text-light @endif"></i>
                    </span>
                    <span>{{$product->sku}}</span>
                    <a href="{{route('products.show', $product)}}">{!! $product->name !!}</a>
                        @if($product->hasPrice)
                        <span class="d-inline-block pull-right product-price font-weight-bold text-big">
                            @if($product->oldprice<>0 && $product->oldprice !=$product->price->value)
                                        <span class="old-price">{{ Site::format($product->oldprice) }}</span>
                                    @endif
                            {{ Site::format($product->price->value) }}</span>
                        @endif
                </div>
            @endforeach
        </div>
        {{$products->render()}}
    </div>
@endsection