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

        {{-- @filter(['repository' => $repository])@endfilter --}}
        @pagination(['pagination' => $retailOrders])@endpagination
        {{$retailOrders->render()}}
        @foreach($retailOrders as $retailOrder)
       
            <div class="card my-2 mb-5" id="retailOrder-{{$retailOrder->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">
                        @lang('site::admin.retail_order.retail_order') № {{$retailOrder->id}}
                        <!--<a href="{{route('admin.retail-orders.show', $retailOrder)}}" class="mr-3 ml-0">
                            
                        </a> -->
                        &nbsp;<span class="badge text-normal badge-pill text-white" style="background-color: {{ $retailOrder->status->color }}">
                            <i class="fa fa-{{ $retailOrder->status->icon }}"></i> {{ $retailOrder->status->name }}
                        </span>
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
                    <div class="col-xl-3 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::admin.retail_order.products')</dt>
                            @foreach($retailOrder->items as $item)
                            <dd class="col-12">{{$item->product->name}} - {{$item->quantity}} шт x {{Cart::price_format($item->price)}} </dd>
                            @endforeach

                          

                        </dl>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">Контакты и комментарии</dt>
                            <dd class="col-12">{{$retailOrder->phone_number}}</dd>
                            <dd class="col-12">{{$retailOrder->contact}}</dd>
                            @if($retailOrder->comment)
                                <dd class="col-12">{{$retailOrder->comment}}</dd>
                            @endif
                            
                        </dl>
                    </div>
                </div>
                @if($retailOrder->statuses()->exists())
                <div class="card-footer with-elements">
                    <form id="order-{{$retailOrder->id}}" action="{{route('retail_orders_esb.status', $retailOrder)}}" method="POST">
                        @csrf
                        @method('PUT')
                                
                        <div class="card-footer-elements ml-md-auto"> Сменить статус заказа:
                             @foreach($retailOrder->statuses()->get() as $status)
                             <button form="order-{{$retailOrder->id}}" type="submit" name="retailOrder[status_id]" value="{{$status->id}}" class="btn btn-sm text-normal text-white m-2" style="background-color: {{ $status->color }}">
                                {{ $status->button }}
                             </a>
                             @endforeach
                        </div>
                    </form>
                </div>
                @endif
            </div>
        @endforeach
        {{$retailOrders->render()}}
    </div>
@endsection
