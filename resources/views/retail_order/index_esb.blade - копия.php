@extends('layouts.app')
@section('title') Заказы с маркета @endsection
@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::user.esb_order.orders')</li>
        </ol>

        @alert()@endalert()

        <div class=" border p-3 mb-2">
            <a href="{{ route('ferroli-user.home') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>

        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $retailOrders])@endpagination
        {{$retailOrders->render()}}
        @foreach($retailOrders as $retailOrder)
       
            <div class="card my-2" id="retailOrder-{{$retailOrder->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">
                        @lang('site::admin.retail_order.retail_order') № {{$retailOrder->id}}
                        <!--<a href="{{route('admin.retail-orders.show', $retailOrder)}}" class="mr-3 ml-0">
                            
                        </a> -->
                    </div>

                </div>
                <div class="row">
                    <div class="col-xl-2">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::messages.created_at')</dt>
                            <dd class="col-12">{{$retailOrder->created_at->format('d.m.Y')}}</dd>
                        </dl>
                    </div>
                    <div class="col-xl-3">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::admin.retail_order.user')</dt>
                            <dd class="col-12">{{$retailOrder->userAddress->user->name}}</dd>
                            <dd class="col-12">{{$retailOrder->userAddress->name}}</dd>
                            <dd class="col-12">{{$retailOrder->userAddress->region->name}}</dd>
                        </dl>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::admin.retail_order.client')</dt>
                            <dd class="col-12">{{$retailOrder->phone_number}}</dd>
                            <dd class="col-12">{{$retailOrder->contact}}</dd>
                            @if($retailOrder->comment)
                                <dd class="col-12">{{$retailOrder->comment}}</dd>
                            @endif
                            
                        </dl>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::admin.retail_order.products')</dt>
                            @foreach($retailOrder->items as $item)
                            <dd class="col-12">{{$item->product->name}} - {{$item->quantity}} шт x {{Cart::price_format($item->price)}} </dd>
                            @endforeach

                          

                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
        {{$retailOrders->render()}}
    </div>
@endsection
