@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::user.esb_product_maintenance.index')</li>
        </ol>

        @alert()@endalert()
        <div class="card mb-2">
            <div class="card-body">
                <a href="{{route('esb-product-maintenances.create')}}" class="btn btn-ms"><i class="fa fa-plus"></i> @lang('site::user.esb_product_maintenance.add')</a>
            </div>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $maintenances])@endpagination
        {{$maintenances->render()}}
        @foreach($maintenances as $maintenance)
            <div class="card my-4" id="mounter-{{$maintenance->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">

                        <a href="{{route('esb-product-maintenances.show', $maintenance)}}" class="mr-3 ml-0">
                            @lang('site::user.esb_product_maintenance.maintenance') № {{$maintenance->number ? $maintenance->number : $maintenance->id}}
                        </a>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::user.esb_product_maintenance.date_maintenance')</dt>
                            <dd class="col-12">{{$maintenance->date->format('d.m.Y')}}</dd>
                            <dt class="col-12">@lang('site::user.esb_user_product.date_sale')</dt>
                            <dd class="col-12">{{$maintenance->esbProduct->date_sale ? $maintenance->esbProduct->date_sale->format('d.m.Y') : 'не указана'}}</dd>
                        </dl>
                    </div>
                    <div class="col-sm-3">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::user.esb_product_maintenance.product')</dt>
                            <dd class="col-12">{{$maintenance->esbProduct->product ? $maintenance->esbProduct->product->name : $maintenance->esbProduct->product_no_cat}}
                                
                            </dd>
                            <dt class="col-12">@lang('site::user.esb_user_product.serial')</dt>
                            <dd class="col-12">{{$maintenance->esbProduct->serial ? $maintenance->esbProduct->serial : 'не указан'}}
                                
                            </dd>
                        </dl>
                    </div>
                    <div class="col-sm-7">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::mounter.address')</dt>
                            <dd class="col-12">{{$maintenance->esbProduct->address ? $maintenance->esbProduct->address_filtred : 'не указан'}}</dd>
                            <dt class="col-12">@lang('site::user.esb_product_maintenance.user')</dt>
                            <dd class="col-12">{{$maintenance->esbProduct->user_filtred}}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
        {{$maintenances->render()}}
    </div>
@endsection
