@extends('layouts.app')
@section('title')Заказы на витрину @endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::stand_order.orders')</li>
        </ol>
        <h1 class="header-title mb-4"><i class="fa fa-@lang('site::stand_order.icon')"></i> @lang('site::stand_order.orders')
        </h1>
        <div class=" border p-3 mb-2">
           <!-- <button form="repository-form"
                    type="submit"
                    name="excel"
                    class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-primary">
                <i class="fa fa-upload"></i>
                <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
            </button> -->
            <a href="{{ route('ferroli-user.home') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
            <a href="{{ route('admin.stand-orders.user') }}" class="d-block d-sm-inline-block btn btn-ms">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.create') @lang('site::stand_order.order')</span>
            </a>
        </div>
        @alert()@endalert()
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $standOrders])@endpagination
        {{$standOrders->render()}}

        @foreach($standOrders as $standOrder)
            <div class="card my-4" id="order-{{$standOrder->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">
                        <a href="{{route('admin.stand-orders.show', $standOrder)}}" class="mr-3">
                            @lang('site::stand_order.order_h') № {{$standOrder->id}}
                        </a>
                        <span class="badge text-normal badge-pill text-white" style="background-color: {{ $standOrder->status->color }}">
                            <i class="fa fa-{{ $standOrder->status->icon }}"></i> {{ $standOrder->status->name }}
                        </span>
                    </div>
                    <div class="card-header-elements ml-md-auto">

                        <a href="{{route('admin.users.show', $standOrder->user)}}">
                            @if($standOrder->user->image->fileExists)
                                <img id="user-logo" src="{{$standOrder->user->logo}}"
                                     style="width:25px!important;height: 25px"
                                     class="rounded-circle mr-2">
                            @endif
                            {{$standOrder->user->name}}
                        </a>
                        @if( $standOrder->messages()->exists())
                            <span class="badge badge-secondary text-normal badge-pill">
                                <i class="fa fa-comment"></i> {{ $standOrder->messages()->count() }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-2 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::messages.created_at')</dt>
                            <dd class="col-12">{{$standOrder->created_at->format('d.m.Y H:i')}}</dd>

                        </dl>
                    </div>
                    <div class="col-xl-6 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::stand_order.address_id')</dt>
                            <dd class="col-12">{{ optional($standOrder->address)->name }} {{ optional($standOrder->address)->full }}</dd>
                            
                            <dt class="col-12">@lang('site::stand_order.warehouse_address_id')</dt>
                            <dd class="col-12">{{ optional($standOrder->warehouse_address)->name }}</dd>
                            
                            <dt class="col-12">Менеджер региона</dt>
                            <dd class="col-12">{{ optional($standOrder->user->region->manager)->name }}@if(Auth()->user()->hasRole('supervisor') || Auth()->user()->hasRole('админ'))
                            <a href="{{route('admin.users.show',$standOrder->user->region->manager)}}"><i class="fa fa-external-link"></i></a>@endif
                            </dd>
                        </dl>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::stand_order.items')</dt>
                            <dd class="col-12">
                                <ul class="list-group"></ul>
                                @foreach($standOrder->items()->with('product')->get() as $item)
                                    <li class="list-group-item border-0 px-0 py-1">
                                        <a href="{{route('products.show', $item->product)}}">
                                            {!!$item->product->name!!} ({{$item->product->sku}})
                                        </a>

                                        x {{$item->quantity}} {{$item->product->unit}}
                                    </li>
                                @endforeach
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
        {{$standOrders->render()}}
    </div>
@endsection
