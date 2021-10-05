@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::product.retail_sale_bonus.retail_sale_bonuses')</li>
        </ol>
        <h1 class="header-title mb-4">
            <i class="fa fa-@lang('site::product.retail_sale_bonus.icon')"></i> @lang('site::product.retail_sale_bonus.retail_sale_bonuses')
        </h1>

        @alert()@endalert()

        <div class=" border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.retail-sale-bonuses.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::product.retail_sale_bonus.retail_sale_bonus')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>

        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $retail_sale_bonuses])@endpagination
        {{$retail_sale_bonuses->render()}}

        @foreach($retail_sale_bonuses as $retail_sale_bonus)
            <div class="card my-0" id="mounting-{{$retail_sale_bonus->id}}">

                <div class="row">
                    <div class="col-sm-5">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::product.retail_sale_bonus.product_id')</dt>
                            <dd class="col-12">
                                <a href="{{route('admin.products.show', $retail_sale_bonus->product)}}">
                                    {{$retail_sale_bonus->product->name}} ({{$retail_sale_bonus->product->sku}})
                                </a>
                            </dd>
                        </dl>
                    </div>
                    <div class="col-sm-7">
                        <dl class="row mt-2">
                            <dt class="col-9">@lang('site::product.retail_sale_bonus.start')</dt>
                            <dd class="col-3">
                                {{number_format($retail_sale_bonus->start, 0, '.', ' ')}}
                             </dd>
                            <dt class="col-9">@lang('site::product.retail_sale_bonus.value')</dt>
                            <dd class="col-3">
                                {{number_format($retail_sale_bonus->value, 0, '.', ' ')}}
                             </dd>
                            <dt class="col-9">@lang('site::product.retail_sale_bonus.profi')</dt>
                            <dd class="col-3">
                                {{number_format($retail_sale_bonus->profi, 0, '.', ' ')}}
                             </dd>
                            <dt class="col-9">@lang('site::product.retail_sale_bonus.super')</dt>
                            <dd class="col-3">
                                {{number_format($retail_sale_bonus->super, 0, '.', ' ')}}
                             </dd>
                        </dl>
                    </div>
                    
                </div>
            </div>
        @endforeach
        {{$retail_sale_bonuses->render()}}
    </div>
@endsection
