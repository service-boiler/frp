@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::user.esb_product_launch.index')</li>
        </ol>

        @alert()@endalert()
        <div class="card mb-2">
            <div class="card-body">
                <a href="{{route('esb-product-launches.create')}}" class="btn btn-ms"><i class="fa fa-plus"></i> @lang('site::user.esb_product_launch.add')</a>
            </div>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $launches])@endpagination
        {{$launches->render()}}
        @foreach($launches as $launch)
            <div class="card my-4" id="mounter-{{$launch->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">

                        <a href="{{route('esb-product-launches.show', $launch)}}" class="mr-3 ml-0">
                            @lang('site::user.esb_product_launch.launch') № {{$launch->number ? $launch->number : $launch->id}}
                        </a>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::user.esb_product_launch.date_launch')</dt>
                            <dd class="col-12">{{$launch->date_launch->format('d.m.Y')}}</dd>
                            <dt class="col-12">@lang('site::user.esb_user_product.date_sale')</dt>
                            <dd class="col-12">{{$launch->esbProduct->date_sale ? $launch->esbProduct->date_sale->format('d.m.Y') : 'не указана'}}</dd>
                        </dl>
                    </div>
                    <div class="col-sm-3">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::user.esb_product_launch.product')</dt>
                            <dd class="col-12">{{$launch->esbProduct->product ? $launch->esbProduct->product->name : $launch->esbProduct->product_no_cat}}
                                
                            </dd>
                            <dt class="col-12">@lang('site::user.esb_user_product.serial')</dt>
                            <dd class="col-12">{{$launch->esbProduct->serial ? $launch->esbProduct->serial : 'не указан'}}
                                
                            </dd>
                        </dl>
                    </div>
                    <div class="col-sm-7">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::mounter.address')</dt>
                            <dd class="col-12">{{$launch->esbProduct->address ? $launch->esbProduct->address_filtred : 'не указан'}}</dd>
                            <dt class="col-12">@lang('site::user.esb_product_launch.user')</dt>
                            <dd class="col-12">{{$launch->esbProduct->user_filtred}}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
        {{$launches->render()}}
    </div>
@endsection
