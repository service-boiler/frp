@extends('layouts.app')
@push('styles')
<style type="text/css">


    #product-row.row.grid .product-col,
    #product-row.row.list .product-col {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
        border: 1px solid #f9f9f9;
    }

    #product-row.row .product-col:hover {
        border: 1px solid #FFECB3;
        background-color: #fcf3d8;
    }

    #product-row.row .product-col .product-image,
    #product-row.row .product-col .product-content {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 50%;
        flex: 0 0 50%;
        max-width: 50%;
    }

    #product-row.row.grid .product-content {
        text-align: center;
    }

    #product-row.row.list .product-content {
        text-align: left;

    }

    #product-row.row .product-details {
        display: none;
    }

    #product-row.row.list .product-content .product-name {
        width: 74%;
        display: inline-block;
    }

    #product-row.row.list .product-content .product-cart {
        text-align: right;
        display: inline-block;
        width: 24%;
    }

    @media (min-width: 576px) {

        #product-row.row.list .product-details {
            display: block;
        }

        #product-row.row.grid .product-col {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }

        #product-row.row.grid .product-col .product-image,
        #product-row.row.grid .product-col .product-content {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }

        #product-row.row.list .product-col .product-image {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
        }

        #product-row.row.list .product-col .product-content {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 75%;
            flex: 0 0 75%;
            max-width: 75%;
        }
    }

    @media (min-width: 768px) {
        #product-row.row.grid .product-col {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 33.33333333%;
            flex: 0 0 33.33333333%;
            max-width: 33.33333333%;
        }

        #product-row.row.list .product-col .product-image {
            -ms-flex: 0 0 20%;
            flex: 0 0 20%;
            max-width: 20%;
        }
    }

    @media (max-width: 992px) {

        #product-row.row.list .product-content .product-name,
        #product-row.row.list .product-content .product-cart {
            width: 100%;
            display: block;
        }
    }

    @media (min-width: 1200px) {
        #product-row.row.grid .product-col {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
        }

        #product-row.row.list .product-col .product-image {
            -ms-flex: 0 0 22.7%;
            flex: 0 0 22.7%;
            max-width: 22.7%;
        }

        #product-row.row.list .product-col .product-content {
            -ms-flex: 0 0 77.3%;
            flex: 0 0 77.3%;
            max-width: 77.3%;
        }

    }
</style>
@endpush
@section('title')@lang('site::product.products')@lang('site::messages.title_separator')@endsection
@section('header')

    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($headBannerBlocks as $headBannerBlock)
               <li data-target="#carouselExampleIndicators" data-slide-to="{{$loop->index}}" @if($loop->index==0) class="active" @endif></li>
            @endforeach
        </ol>
            <div class="carousel-inner">
                @foreach($headBannerBlocks as $headBannerBlock)
                    <div class="carousel-item @if($loop->index==0) active @endif">
                    @if(!empty($headBannerBlock->url) & $headBannerBlock->url!='#' )
                    <a href="{{$headBannerBlock->url}}" target="_blank"><img src="{{$headBannerBlock->image->src()}}" alt=""></a>
                    @else
                    <img src="{{$headBannerBlock->image->src()}}" alt="">
                    @endif
                    </div>
                @endforeach
            </div>	


        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">@lang('site::messages.prev')</span></a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">@lang('site::messages.next')</span></a>
    </div>

    @include('site::header.front',[
        'h1' => __('site::product.products'),
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['name' => __('site::product.products')]
        ]
    ])
@endsection
@section('content')

    <div class="container">
        @can('product_list', Auth::user())
            <div class=" border p-3 mb-2">
                <a href="{{route('products.list')}}" class="d-block d-sm-inline btn btn-ms">
                    <i class="fa fa-list-alt"></i> @lang('site::messages.show') @lang('site::product.view.list')
                </a>
            </div>
        @endcan
        <a name="filter"></a>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $products])@endpagination
        {{$products->render()}}
        {{--<div class="btn-group btn-group-toggle" data-toggle="buttons">--}}
        {{--<label class="btn btn-ms active" onclick="gridView()">--}}
        {{--<input type="radio"--}}
        {{--name="options" id="option2" autocomplete="off" checked>--}}
        {{--<i class="fa fa-th-large"></i> Таблица--}}
        {{--</label>--}}
        {{--<label class="btn btn-ms" onclick="listView()">--}}
        {{--<input type="radio"--}}
        {{--name="options" id="option1" autocomplete="off">--}}
        {{--<i class="fa fa-bars"></i> Список--}}
        {{--</label>--}}
        {{--</div>--}}
        <div id="product-row" class="row grid mb-2">
            @foreach($products as $key => $product)
                <div class="product-col mt-3">
                    <div class="row p-2 mx-2">

                        <div class="product-image">
                            <a href="{{route('products.show', $product)}}">
                                <img class="img-fluid" src="{{ $product->image()->src() }}" alt="{{$product->name}}">
                            </a>
                        </div>

                        <div class="product-content pl-2 pl-sm-0 pt-sm-2" style="position: relative;height: 150px;">
                            <div class="product-name">
                                <a class="text-dark"
                                   href="{{route('products.show', $product)}}">{!! str_limit($product->name, 60) !!}</a>
                                <div class="text-muted">@lang('site::product.sku'): {{$product->sku}}
                                </div>
                                <div class="product-details mt-2">
                                    @if( $product->relation_equipments()->count() > 0)
                                        @lang('site::relation.relations')
                                        : {{ $product->relation_equipments()->implode('name', ', ') }}
                                    @endif
                                </div>
                            </div>
                            <div class="product-cart" style="position: absolute;bottom:0;width: 100%;">
                                @if($product->hasPrice &&  $product->showRetPrice())
                                
                                    @if($product->oldprice<>0 && $product->oldprice !=$product->price->value)
                                        <div class="product-price font-weight-bold text-xlarge mt-3"><span class="old-price">{{ Site::format($product->oldprice) }}</span> {{ Site::format($product->price->value) }}</div>
                                        <span class="d-block text-muted mb-3">Распродажа</span>
                                    @else
                                
                                    <div class="product-price font-weight-bold text-xlarge mt-3">{{ Site::format($product->price->value) }}</div>
                                    <span class="d-block text-muted mb-3">{{ $product->price->type->display_name ?: __('site::price.price')}}</span>
                                    @endif
                                @else
                                    <div style="height: 36px!important">{{-- @lang('site::price.price') @lang('site::price.help.price') --}}</div>
                                @endif
								@if($product->for_preorder)
                                @include('site::cart.buy.large')
								@else 
								@lang('site::product.not_available')
								@endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{--@each('site::product.grid', $items, 'product', 'site::product.empty')--}}
        </div>
        {{$products->render()}}
    </div>
@endsection
@push('scripts')
{{--<script type="text/javascript">--}}

{{--let product_row = document.getElementById("product-row");--}}

{{--// List View--}}
{{--function listView() {--}}

{{--product_row.classList.remove("grid");--}}
{{--product_row.classList.add("list");--}}
{{--}--}}

{{--// Grid View--}}
{{--function gridView() {--}}
{{--product_row.classList.remove("list");--}}
{{--product_row.classList.add("grid");--}}
{{--}--}}
{{--</script>--}}
@endpush