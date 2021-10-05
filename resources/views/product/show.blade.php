@extends('layouts.app')
@section('title'){{$product->title ?: ($product->equipment ? $product->equipment->name : $product->name) }} @lang('site::messages.title_separator')@endsection
@section('description'){{$product->metadescription ?: $product->name }}@endsection
@section('header')
    @include('site::header.front',[
        'h1' => '',
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['url' => $product->equipment ? route('catalogs.index') : route('products.index'), 'name' => $product->equipment ? __('site::catalog.catalogs') : __('site::product.products')],
            $product->equipment ? ['url' => route('equipments.show', $product->equipment), 'name' => $product->equipment->name] : null,
            ['name' => $product->name],
        ]
    ])
@endsection
@section('content')

    <div class="container">
        <div class="card border-0 mb-3">
            <div class="card-body">
                <div class="media flex-wrap flex-lg-nowrap">

                    <div id="carouselEquipmentIndicators" class="carousel slide col-12 col-sm-3 col-lg-5 p-0"
                         data-ride="carousel">

                        <ol class="carousel-indicators">
                            @if($images->isNotEmpty())
                                @foreach($images as $key => $image)
                                    <li data-target="#carouselEquipmentIndicators" data-slide-to="{{$key}}"
                                        @if($key == 0) class="active" @endif></li>
                                @endforeach
                            @endif
                        </ol>
                        <div class="carousel-inner">
                            @if($images->isNotEmpty())
                                @foreach($images as $key => $image)
                                    <div class="carousel-item @if($key == 0) active @endif">
                                        <img class="d-block w-100"
                                             src="{{ $image->src() }}"
                                             alt="{{$product->name}}">
                                    </div>
                                @endforeach
                            @else
                                <div class="carousel-item active">
                                    <img class="d-block border w-100"
                                         src="{{ $product->image()->src() }}"
                                         alt="{{$product->name}}">
                                </div>
                            @endif

                        </div>
                        @if($images->count() > 1)
                            <a class="carousel-control-prev" href="#carouselEquipmentIndicators" role="button"
                               data-slide="prev">
                                <span class="carousel-control-prev-icon dark" aria-hidden="true"></span>
                                <span class="sr-only">@lang('site::messages.prev')</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselEquipmentIndicators" role="button"
                               data-slide="next">
                                <span class="carousel-control-next-icon dark" aria-hidden="true"></span>
                                <span class="sr-only">@lang('site::messages.next')</span>
                            </a>
                        @endif
                    </div>


                    <div class="row">
                        <div class="media-body col-sm-9 pl-md-5 px-4 pb-4">
                            <h1>{{$product->h1 ?: $product->name }}</h1>

                            <dl class="row">

                                @admin()
                                <dd class="col-sm-12"><a href="{{route('admin.products.show', $product)}}">
                                        <i class="fa fa-folder-open"></i>
                                        @lang('site::messages.open')
                                            @lang('site::messages.in_admin')
                                    </a>
                                </dd>
                                @endadmin()

                                <dt class="col-sm-4">@lang('site::product.sku')</dt>
                                <dd class="col-sm-8">{{$product->sku}}</dd>

                                @if($analogs->isNotEmpty() || $product->old_sku)
                                    <dt class="col-sm-4">@lang('site::analog.analogs')</dt>
                                    <dd class="col-sm-8">{!! $product->analogs_array()->implode(', ') !!}</dd>
                                @endif
                                @if($product->brand_id)
                                    <dt class="col-sm-4">@lang('site::product.brand_id')</dt>
                                    <dd class="col-sm-8">{!! $product->brand->name !!}</dd>
                                @endif
                                @if($product->type_id)
                                    <dt class="col-sm-4">@lang('site::product.type_id')</dt>
                                    <dd class="col-sm-8">{{$product->type->name}}</dd>
                                @endif
                                @if($product->equipment)
                                    <dt class="col-sm-4">@lang('site::product.equipment_id')</dt>
                                    <dd class="col-sm-8">
                                        <a href="{{route('equipments.show', $product->equipment)}}">
                                            {{$product->equipment->name}}
                                        </a>
                                    </dd>
                                @endif
                                <dt class="col-sm-4">@lang('site::product.quantity')</dt>
                                <dd class="col-sm-8">
                                    @if($storehouse_addresses->isNotEmpty())
                                        <div>
                                        <table class="table table-sm">
                                            @foreach($storehouse_addresses as $address)
                                                <tr>
                                                    <td class="pl-0">{{$address['name']}}</td>
                                                    <td class="text-right pr-0">@if(!empty($address['updated_at'])){{$address['updated_at']->format('d.m.Y')}} @endif
                                                        <span class="badge badge-success">
                                                        {{number_format($address['quantity'], 0, '.', ' ')}} {{$product->unit}}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            
                                            
                                            
                                            
                                        </table>
                                        </div>
                                        
                                    @elseif($product->for_preorder)
										<span class="badge badge-light d-block d-md-inline-block">@lang('site::product.not_available')</span>
									@else
                                        <span style="font-weight:bold">@lang('site::product.not_preorder')</span>
                                    @endif 

                                </dd>

                                    @if($product->hasPrice)
                                        <dt class="col-sm-4">{{ $product->price->type->display_name ?: __('site::price.price')}}</dt>
                                        @if($product->pricepromo->value<>0 && $product->price->value !=$product->pricepromo->value)
                                                    <dd class="col-sm-8 h3"><span class="old-price">{{ Site::format($product->price->value) }}<span></dd>
                                        @else
                                                    <dd class="col-sm-8 h2">{{ Site::format($product->price->value) }}</dd>
                                        @endif
                                        
                                        @if($product->pricepromo->value<>0 && $product->price->value !=$product->pricepromo->value)
                                            <dt class="col-sm-4">{{ $product->pricepromo->type->display_name ?: __('site::price.price')}}</dt>
                                            <dd class="col-sm-8 h2">{{ Site::format($product->pricepromo->value) }}</dd>
                                        @endif
                                        
                                        @else
                                            <dt class="col-sm-4">@lang('site::price.price')</dt>
                                            <dd class="col-sm-8">@lang('site::price.help.price')</dd>
                                    @endif
                                    <dt class="col-sm-4"></dt>
                                    <dd class="col-sm-8">
                                        @if(($product->forsale || $product->for_preorder) & in_array($product->group->type->id, ['2','3']))
                                           @include('site::cart.buy.large')
                                        @endif
                                    </dd>

                            </dl>


                            
                            </div></div>

                        </div>
                    </div>

                </div>
                
                @alert()@endalert()

                 <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                    @if($product->description)
                        <li class="nav-item">
                            <a class="nav-link @if($product->type_id<4  && !$errors->any()) active @endif" id="description-tab"
                               data-toggle="tab" href="#description"
                               role="tab"
                               aria-controls="description" aria-selected="true">
                                @lang('site::product.description')
                            </a>
                        </li>
                    @endif

                    @if($equipments->isNotEmpty())
                        <li class="nav-item">
                            <a class="nav-link @if($product->type_id>3 && !$errors->any()) active @endif" id="back-relation-tab"
                               data-toggle="tab" href="#back-relation"
                               role="tab"
                               aria-controls="back-relation"
                               aria-selected="false">@lang('site::relation.relations')
                                    <span class="text-big badge badge-secondary">
                                    {{$back_relations->count()}}
                                </span>
                            </a>
                        </li>
                    @endif

                    @if($analogs->isNotEmpty())
                        <li class="nav-item">
                            <a class="nav-link" id="analog-tab" data-toggle="tab" href="#analog" role="tab"
                               aria-controls="analog" aria-selected="false">@lang('site::analog.analogs')
                                    <span class="text-big badge badge-secondary">
                                {{$analogs->count()}}
                            </span>
                            </a>
                        </li>
                    @endif

                </ul>
                <div class="tab-content" id="myTabContent">
                    @if($product->description)
                        <div class="tab-pane fade @if($product->type_id<4 && !$errors->any()) show active @endif p-3" id="description"
                             role="tabpanel"
                             aria-labelledby="description-tab">{!! $product->description !!}
                              @if(!empty($product_description_addon))<br /><br /><p><strong>{!!$product_description_addon!!}</p></strong>@endif
                        </div>
                    @endif

                    
                    @if($equipments->isNotEmpty())
                        <div class="tab-pane fade @if($product->type_id>3 && !$errors->any()) show active @endif p-3" id="back-relation"
                             role="tabpanel"
                             aria-labelledby="back-relation-tab">
                            <table class="table">
                                <tbody>
                                @foreach($equipments as $equipment)
                                    <tr>
                                        <td>
                                            <a class="d-block text-large"
                                               href="{{route('equipments.show', $equipment)}}">{!! $equipment->name !!}</a>
                                        </td>
                                        <td>
                                            @foreach($back_relations as $back_relation)
                                                @if($back_relation->equipment_id == $equipment->id)
                                                    <a class="d-block"
                                                       href="{{route('products.show', $back_relation)}}">{!! $back_relation->name !!}</a>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif


                    @if($analogs->isNotEmpty())
                        <div class="tab-pane fade p-3" id="analog" role="tabpanel" aria-labelledby="analog-tab">

                            @foreach($analogs as $analog)
                                <div class="row border-bottom p-1">
                                    <div class="col-sm-8">
                                        <span>{{$analog->sku}}</span>
                                        <a href="{{route('products.show', $analog)}}">{!! $analog->name !!}</a>
                                    </div>
                                    <div class="col-sm-2">
                                        @if($analog->quantity > 0)
                                            <span class="badge badge-success d-block d-md-inline-block">@lang('site::product.in_stock')</span>
                                        @else
                                            <span class="badge badge-light d-block d-md-inline-block">@lang('site::product.not_available')</span>
                                        @endif
                                    </div>
                                    <div class="col-sm-2 text-left text-sm-right">
                                        @if($analog->hasPrice)
                                            {{ Site::format($analog->price->value) }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif




                </div>
            </div>

        </div>
    </div>
@endsection
