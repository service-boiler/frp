@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="header-title mb-4">Ваш заказ № {{ $order->id }}</h1>
        @alert()@endalert()

        <div class="row mb-2">
            <div class="col-12">
                <div class="card mb-2">
                    <h6 class="card-header with-elements">
                        <span class="card-header-title">@lang('site::order.info')</span>
                    </h6>
                    <div class="card-body">

                        <div class="mb-2">
                            <span class="text-muted">@lang('site::order.created_at'):</span>&nbsp;
                            <span class="text-dark">{{$order->created_at->format('d.m.Y H:i' )}}</span>
                            <span class="text-muted ml-3">@lang('site::order.status_id'):</span>&nbsp;
                            <span class="badge text-normal badge-pill text-white"
                                  style="background-color: {{ $order->status->color }}">
                                <i class="fa fa-{{ $order->status->icon }}"></i> {{ $order->status->name }}
                            </span>
                        </div>

                        <div class="mb-2">
                            <span class="text-muted">Имя получателя:</span>&nbsp;
                            <span class="text-dark">{{ $order->user->name }}</span>
                        </div>
                    @if($order->contragent)
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::order.contragent_id'):</span>&nbsp;
                            <span class="text-dark">{{ $order->contragent->name }}</span>
                        </div>
                    @endif

                        <div class="mb-2">
                            <span class="text-muted">Адрес доставки:</span>&nbsp;
                            <span class="text-dark">{{ $order->shipping_address }}</span>
                        </div>

                    </div>
                        <h6 class="card-header with-elements">
                            <span class="card-header-title">@lang('site::order.items')</span>
                        </h6>
                        <div class="card-body">
                            @foreach($order->items as $item)
                                <hr class="mb-3"/>
                                <div class="row mb-sm-1">

                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-3 d-none d-md-block">
                                                <img class="img-fluid img-thumbnail"
                                                     src="{{ $item->product->image()->src() }}">
                                            </div>
                                            <div class="col-sm-9">
                                                <a class="d-block"
                                                   href="{{route('products.show', $item->product)}}">{!! $item->product->name() !!}</a>
                                                <div class="text-muted">
                                                    {{ $item->quantity }} {{ $item->product->unit }} x
                                                    {{Site::convert($item->price, $item->currency_id, $item->currency_id, 1, false, true)}}
                                                    @if ($item->currency_id != 643)
                                                        ({{Site::convert($item->price, $item->currency_id, 643, 1, false, true)}}
                                                        )
                                                    @endif
                                                </div>
                                                @if($order->in_stock_type == 2)
                                                    <div class="text-muted">
                                                        @lang('site::order_item.weeks_delivery'):
                                                        @if($item->weeks_delivery > 0)
                                                            {{numberof($item->weeks_delivery, $item->weeks_delivery, trans('site::messages.weekends'))}}
                                                        @else
                                                            @lang('site::messages.not_indicated_m')
                                                        @endif
                                                    </div>
                                                @endif

                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-sm-4 mb-4 mb-sm-0 text-large text-left text-sm-right">
                                        {{Site::convert($item->price, $item->currency_id, $item->currency_id, $item->quantity, false, true)}}
                                        @if ($item->currency_id != 643)
                                            ({{Site::convert($item->price, $item->currency_id, 643, $item->quantity, true, true)}}
                                            )
                                        @endif
                                    </div>
                                </div>

                            @endforeach
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <div class="font-weight-bold mb-0">@lang('site::order.total'):</div>&nbsp;
                                <div class="text-dark display-6 font-weight-bolder">
                                    @include('site::order.total')
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="font-weight-bold text-muted mb-0">
                                    @if(in_array($order->status_id,['1','2']))
                                        Возможность оплаты заказа будет доступна после проверки и подтверждения нашим менеджером.
                                    @endif

                                </div>

                            </div>


                        </div>
                    @if($order->file)
                        <hr class="p-0"/>
                        <div class="mb-2">
                            <div class="font-weight-bold mb-0">@lang('site::order.help.payment')</div>&nbsp;
                            <div class="form-row bg-white">
                                @include('site::file.download', ['file' => $order->file])
                            </div>
                        </div>
                    @endif
                </div>
            </div>


        </div>
    </div>
@endsection
