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
            <li class="breadcrumb-item">
                <a href="{{ route('stand-orders.index') }}">@lang('site::stand_order.breadcrumb_index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::stand_order.breadcrumb_show', ['standOrder' => $standOrder->id, 'date' => $standOrder->created_at->format('d.m.Y H:i') ])</li>
        </ol>
        <h1 class="header-title mb-4">№ {{ $standOrder->id }}</h1>
        @alert()@endalert()

        <div class=" border p-3 mb-2">
            <a href="{{ route('stand-orders.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
        </div>

        <div class="row mb-2">
            <div class="col-xl-4">
                <div class="card mb-2">
                    <h6 class="card-header with-elements">
                        <span class="card-header-title">@lang('site::stand_order.info')</span>
                    </h6>
                    <div class="card-body">

                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.created_at'):</span>&nbsp;
                            <span class="text-dark">{{$standOrder->created_at->format('d.m.Y H:i' )}}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.status_id'): {{ $standOrder->status->id }}</span>&nbsp;
                            <div class="text-normal badge-pill text-white"
                                  style="background-color: {{ $standOrder->status->color }}">
                                <i class="fa fa-{{ $standOrder->status->icon }}"></i> {{ $standOrder->status->name }}
                            </div>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.contragent_id'):</span>&nbsp;
                            <span class="text-dark">{{ $standOrder->contragent->name }}</span>
                        </div>

                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.address_id'):</span>&nbsp;
                            <span class="text-dark">{{ optional($standOrder->address)->name }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.warehouse_address_id'):</span>
                            <span class="text-dark">{{ optional($standOrder->warehouse_address)->name }}</span>
                            @if($standOrder->status_id == 1)
                                <a onclick="document.getElementById('warehouse-address-edit-form').classList.toggle('d-none')"
                                   class="py-0 px-1"
                                   href="javascript:void(0)">
                                   <i class="fa fa-pencil"></i>
                                </a>
                                
                                <fieldset id="warehouse-address-edit-form"
                                      class="d-none border-top mt-4">
                                    <form method="POST"
                                          action="{{route('stand-orders.update', $standOrder)}}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group">
                                            <select required
                                                    class="form-control{{  $errors->has('standOrder.warehouse_address_id') ? ' is-invalid' : '' }}"
                                                    name="standOrder[warehouse_address_id]"
                                                    id="warehouse_address_id">
                                                @if($warehouses->count() == 0 || $warehouses->count() > 1) <option value="">@lang('site::messages.select_from_list')</option>
                                                @endif
                                                @foreach($warehouses as $warehouse_address)
                                                    <option
                                                            @if(old('standOrder.warehouse_address_id') == $warehouse_address->id) selected
                                                            @endif
                                                            value="{{ $warehouse_address->id }}">
                                                        {{ $warehouse_address->user->name }} - {{$warehouse_address->region->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fa fa-@lang('site::address.icon')"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-success py-1" type="submit">
                                            <i class="fa fa-save"></i> @lang('site::messages.save')
                                        </button>
                                    </form>
                                </fieldset>
                            @endif
                                   
                        </div>
                        <div class="mb-2">
                            <a target="_blank" href="{{route('addresses.images.index',$standOrder->address)}}">Фотографии витрины и магазина.</a>
                        </div>
                        <hr class="p-0"/>

                        <div class="mb-2">
                            <div class="font-weight-bold mb-0">@lang('site::stand_order.total'):</div>&nbsp;
                            <div class="text-dark display-6 font-weight-bolder">
                                @include('site::stand_order.total')
                            </div>
                        </div>
                        @if($standOrder->file)
                            <hr class="p-0"/>
                            <div class="mb-2">
                                <div class="font-weight-bold mb-0">@lang('site::stand_order.payment')</div>&nbsp;
                                <div class="form-row bg-white">
                                    @include('site::file.download', ['file' => $standOrder->file])
                                </div>
                            </div>
                        @endif
                        <hr class="p-0"/>
                        <div>
                            <form id="form" action="{{route('stand-orders.update', $standOrder)}}"
                                  method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="d-flex flex-column justify-content-between">

                                @foreach($statuses as $key => $status)
                                    <button type="submit"
                                            name="standOrder[status_id]"
                                            value="{{$status->id}}"
                                            form="form"
                                            style="background-color: {{$status->color}};color:white"
                                            class="btn mb-1">
                                        <i class="fa fa-{{$status->icon}}"></i>
                                        <span>{{$status->button}}</span>
                                    </button>
                                @endforeach
                                <br>

                                <!--   
                                        @if($standOrder->status_id == 7)
                                            <div class="alert alert-warning" role="alert">
                                                @lang('site::stand_order.help.confirm')
                                            </div>
                                            <button name="standOrder[status_id]"
                                                    value="8"
                                                    type="submit"
                                                    class="btn btn-success mb-1">
                                                @lang('site::stand_order.button.status.8')
                                            </button>
                                        @endif
                                   
                                    @if(!in_array($standOrder->status_id, array(12,6)) )
                                        <button name="standOrder[status_id]"
                                                value="6"
                                                type="submit"
                                                class="btn btn-danger mb-1">
                                            @lang('site::stand_order.button.status.6')
                                        </button>
                                    @endif
                                   
                                  @if(!in_array($standOrder->status_id, array(12,6,1)) )
                                        <button name="standOrder[status_id]"
                                                value="12"
                                                type="submit"
                                                class="btn btn-primary mb-1">
                                            @lang('site::stand_order.button.status.12')
                                        </button>
                                    @endif
                                    -->
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-xl-8">
            @if(in_array($standOrder->status_id, array(2,3,4,5)) )
                <div class="card mb-2">
                <div class="card-body bg-danger text-white text-center text-large px-3 d-inline-block">
                    <a class="text-white" style="line-height: 130%;" target="_blank" href="{{route('addresses.images.index',$standOrder->address)}}">@lang('site::stand_order.image_needed')</a>
                </div>
                </div>
            @endif
                <div class="card mb-2">
                    <h6 class="card-header with-elements">
                        <span class="card-header-title">@lang('site::stand_order.items')</span>
                    </h6>
                    <div class="card-body">
                        @foreach($standOrder->items as $item)
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
                                            @if($standOrder->in_stock_type == 2)
                                                <div class="text-muted">
                                                    @lang('site::stand_order_item.weeks_delivery'):
                                                    @if($item->weeks_delivery > 0)
                                                        {{numberof($item->weeks_delivery, $item->weeks_delivery, trans('site::messages.weekends'))}}
                                                    @else
                                                        @lang('site::messages.not_indicated_m')
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="my-2">

                                                <button @cannot('delete', $item) disabled @endcannot
                                                class="py-0 px-1 btn btn-danger btn-sm btn-row-delete"
                                                        data-form="#order-item-delete-form-{{$item->id}}"
                                                        data-btn-delete="@lang('site::messages.delete')"
                                                        data-btn-cancel="@lang('site::messages.cancel')"
                                                        data-label="@lang('site::messages.delete_confirm')"
                                                        data-message="@lang('site::messages.delete_sure') {!! $item->product->name() !!}? "
                                                        data-toggle="modal" data-target="#form-modal"
                                                        href="javascript:void(0);"
                                                        title="@lang('site::messages.delete')">
                                                    <i class="fa fa-close"></i> @lang('site::messages.delete')
                                                </button>
                                                <form id="order-item-delete-form-{{$item->id}}"
                                                      action="{{route('order-items.destroy', $item)}}"
                                                      method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>

                                            </div>
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
                       
                    
                </div><div class="">
                
             @include('site::message.create', ['messagable' => $standOrder])
		</div>
        <!--
                @if($standOrder->status_id != 1)
                            <div class="card mt-2 mb-2">
                            <div class="card-body">
                            <div class="row p-3"><a name="image_add"></a>
                               @lang('site::stand_order.image_needed')
                               
                            </div>
                            <hr />
                                <fieldset>
                                    <div class="row">
                                        <div class="col-xl-6"> 
                                            <h5 class="card-title">@lang('site::stand_order.images')</h5>
                                        </div>
                                        <div class="col-xl-6">
                                            <button type="submit" form="form"
                                                    class="btn btn-success d-block d-sm-inline-block">
                                                <i class="fa fa-check"></i>
                                                <span>@lang('site::stand_order.image_add')</span>
                                            </button>
                                        </div>
                                    </div>
                                    @include('site::file.create.type')
                                    <h6 class="card-subtitle mb-2 text-muted">@lang('site::file.maxsize5mb')</h6>
                                </fieldset>
                            </div>
                        </div>
                 @endif
                 -->
            </div>
        </div>
    </div>
@endsection
