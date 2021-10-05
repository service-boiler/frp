@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::stand_order.orders')</li>
        </ol>
        <h1 class="header-title mb-4"><i class="fa fa-shopping-cart"></i> @lang('site::stand_order.orders')</h1>
        <div class=" border p-3 mb-2">
            <a href="{{ route('stand-orders.create') }}"
               class="d-block d-sm-inline btn mr-0 mr-sm-1 mb-1 mb-sm-0 btn-ms">
                <i class="fa fa-magic"></i>
                <span>@lang('site::messages.create') @lang('site::stand_order.order')</span>
            </a>
            <a href="{{ route('home') }}"
               class="d-block d-sm-inline btn btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.home')</span>
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
                        <a href="{{route('stand-orders.show', $standOrder)}}" class="mr-3">
                            @lang('site::stand_order.order_h') № {{$standOrder->id}}
                        </a>
                        <span class="badge text-normal badge-pill text-white" style="background-color: {{ $standOrder->status->color }}">
                            <i class="fa fa-{{ $standOrder->status->icon }}"></i> {{ $standOrder->status->name }}
                        </span>
                    </div>

                    <div class="card-header-elements ml-md-auto">
                         @if( $standOrder->messages()->exists())
                            <span data-toggle="tooltip"
                                  data-placement="top"
                                  title="@lang('site::message.messages')"
                                  class="badge badge-secondary text-normal badge-pill">
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
