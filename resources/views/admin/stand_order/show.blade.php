@extends('layouts.app')
@section('title')Заказ на витрину {{$standOrder->id}} {{$standOrder->user->name}} @endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.stand-orders.index') }}">@lang('site::stand_order.orders')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::stand_order.breadcrumb_show', ['standOrder' => $standOrder->id, 'date' => $standOrder->created_at->format('d.m.Y H:i') ])</li>
        </ol>
        <h1 class="header-title mb-4">№ {{ $standOrder->id }}</h1>
        @alert()@endalert()

        <div class=" border p-3 mb-2">
           
            <a href="{{ route('admin.stand-orders.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            <a target="_blank" href="{{ route('admin.stand-orders.print', $standOrder) }}" class="d-block d-sm-inline-block btn btn-primary">
                <i class="fa fa-print"></i>
                <span>@lang('site::messages.print')</span>
            </a>
            @if(in_array($standOrder->status->id,[10,11]) && !$standOrder->order_id && ($standOrder->user->hasRole('distr') || $standOrder->user->hasRole('gen_distr')))
                <a href="{{ route('admin.stand-orders.order-create', $standOrder) }}"
                    class="d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0 p-2 btn btn-green">
                        <i class="fa fa-shopping-basket"></i>
                        <span>Создать заказ для 1С</span>
                </a>
                @endif
                @if($standOrder->order_id)
                <a href="{{ route('admin.orders.show', $standOrder->order_id) }}"
                    class="d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0 p-2 btn btn-ms">
                        <i class="fa fa-shopping-basket"></i>
                        <span>Перейти к заказу</span>
                </a>
                @endif
        </div>

        <div class="row">
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
                            <span class="text-muted">@lang('site::stand_order.status_id'): {{$standOrder->status_id}}</span>&nbsp; <br/>
                            <div class="text-normal badge-pill text-white"
                                  style="background-color: {{ $standOrder->status->color }}">
                                <i class="fa fa-{{ $standOrder->status->icon }}"></i> {{ $standOrder->status->name }}
                            </div>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Заказ:</span>&nbsp;
                            @if($standOrder->order)
                                <a class="badge-pill" style="background-color: {{ $standOrder->order->status->color }}" 
                                    href="{{route('admin.orders.show', $standOrder->order)}}">
                                <i class="fa fa-{{ $standOrder->order->status->icon }}"></i> № {{ $standOrder->order->id}} {{ $standOrder->order->status->name }}</a>
                            
                            @else
                            Заказ не создавался
                            @endif
                        </div>

                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.user_id'):</span>&nbsp;
                            <span class="text-dark">
                                <a href="{{route('admin.users.show', $standOrder->user)}}">{{ $standOrder->user->name }}</a>
                            </span>
                        </div>

                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.contragent_id'):</span>&nbsp;
                            <span class="text-dark">
                                <a href="{{route('admin.contragents.show', $standOrder->contragent)}}">{{ $standOrder->contragent->name }}</a>
                            </span>
                        </div>

                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.email'):</span>&nbsp;
                            <span class="text-dark">{{$standOrder->user->email}}</span>
                        </div>

                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.address_id'):</span>&nbsp;
                            <span class="text-dark"><a target="_blank" href="{{route('admin.addresses.show', $standOrder->address)}}">{{ optional($standOrder->address)->name }}</a></span>
                            <span class="text-dark">{{ optional($standOrder->address)->full }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.warehouse_address_id'):</span>
                            <a onclick="document.getElementById('warehouse-address-edit-form').classList.toggle('d-none')"
                               class="py-0 px-1"
                               href="javascript:void(0)">
                               <span class="text-dark">{{ optional($standOrder->warehouse_address)->name }}</span><i class="fa fa-pencil"></i>
                            </a>
                            
                            <fieldset id="warehouse-address-edit-form"
                                  class="d-none border-top mt-4">
                                <form method="POST"
                                      action="{{route('admin.stand-orders.update', $standOrder)}}">
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
                        
                                                
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.shipped_at'):</span>&nbsp;
                            
                        
                            <a onclick="document.getElementById('created-at-edit-form').classList.toggle('d-none')"
                               class="py-0 px-1"
                               href="javascript:void(0)">
                               <span class="text-dark">@if(!empty($standOrder->shipped_at)){{ $standOrder->shipped_at->format('d.m.Y') }}@endif</span>
                                <i class="fa fa-pencil"></i></a>
                                
                            </a>
                          </div>   
                            <fieldset id="created-at-edit-form"
                                  class="d-none border-top mt-4">
                                <form method="POST"
                                      action="{{route('admin.stand-orders.update', $standOrder)}}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group date datetimepicker" id="datetimepicker_shipped_at"
                                             data-target-input="nearest">
                                            <input type="text"
                                                   name="standOrder[shipped_at]"
                                                   id="shipped_at"
                                                   maxlength="10"
                                                   
                                                   data-target="#datetimepicker_shipped_at"
                                                   data-toggle="datetimepicker"
                                                   class="datetimepicker-input form-control{{ $errors->has('standOrder.shipped_at') ? ' is-invalid' : '' }}"
                                                   value="{{ old('shipped_at', !empty($standOrder->shipped_at) ? $standOrder->shipped_at->format('d.m.Y'): '' ) }}">
                                            <div class="input-group-append"
                                                 data-target="#datetimepicker_shipped_at"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    
                                   
                                    <button class="btn btn-success py-1" type="submit">
                                        <i class="fa fa-save"></i>
                                        @lang('site::messages.save')
                                    </button>
                                </form>
                            </fieldset>
                  
                  <hr class="p-0"/>

                        <div class="mb-2">
                            <div class="font-weight-bold mb-0">@lang('site::stand_order.total')</div>&nbsp;
                            <div class="text-dark display-6 font-weight-bolder">
                                @include('site::stand_order.total')
                            </div>
                        </div>

                        <hr class="p-0"/>
                        <div class="mb-2">
                            <div class="font-weight-bold mb-0">
                                @lang('site::stand_order.payment')
                            </div>
                            @if(in_array($standOrder->status_id, array(1,2,3,10,11)))
                                <div class="form-row mt-2">
                                    <div class="col">

                                        <form method="POST" enctype="multipart/form-data"
                                              action="{{route('admin.stand-orders.payment', $standOrder)}}">
                                            @csrf
                                            <input type="hidden"
                                                   name="type_id"
                                                   value="22"/>
                                            <input type="hidden"
                                                   name="storage"
                                                   value="payments"/>
                                            <input class="d-inline-block form-control-file{{ $errors->has('file_id') ? ' is-invalid' : '' }}"
                                                   type="file"
                                                   accept="{{config('site.payments.accept')}}"
                                                   name="path"/>

                                            <button type="submit"
                                                    class="py-0 px-1 btn-sm btn btn-ms file-upload-button">
                                                @lang('site::messages.attach')
                                            </button>
                                            <span class="invalid-feedback">{{ $errors->first('file_id') }}</span>
                                        </form>
                                    </div>
                                </div>
                            @endif
                            <div id="files" class="form-row bg-white">
                                @include('site::admin.file.edit', ['delete' => in_array($standOrder->status_id, array(2,3))])
                            </div>
                        </div>

                        <hr class="p-0"/>
                        <div>
                            <form action="{{route('admin.stand-orders.update', $standOrder)}}"
                                  method="POST" id="form">
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
                                
                                    @if($standOrder->status_id == 1)
                                        <button name="standOrder[status_id]"
                                                value="2"
                                                type="submit"
                                                class="btn btn-primary mb-1">
                                            @lang('site::stand_order.button.status.2')
                                        </button>
                                        <button name="standOrder[status_id]"
                                                value="10"
                                                type="submit"
                                                class="btn btn-secondary mb-1">
                                            @lang('site::stand_order.button.status.10')
                                        </button>
                                    @endif
                                    @if($standOrder->status_id == 10)
                                    <button name="standOrder[status_id]"
                                                value="11"
                                                type="submit"
                                                class="btn btn-secondary mb-1">
                                            @lang('site::stand_order.button.status.11')
                                        </button>
                                    @endif
                                    @if($standOrder->status_id != 6)   
                                    <button name="standOrder[status_id]"
                                            value="6"
                                            type="submit"
                                            class="btn btn-danger mb-1">
                                        @lang('site::stand_order.button.status.6')
                                    </button>
                                    @endif
                                    @if($standOrder->status_id != 1) 
                                    <button name="standOrder[status_id]"
                                            value="1"
                                            type="submit"
                                            class="btn btn-primary mb-1">
                                        @lang('site::stand_order.button.status.1')
                                    </button>
                                    @endif
                                    @if(in_array($standOrder->status_id, array(12,11,10,5)) )
                                        <button name="standOrder[status_id]"
                                                value="13"
                                                type="submit" 
                                                class="btn mb-1 btn-green">
                                            @lang('site::stand_order.button.status.13')
                                        </button>
                                    @endif -->
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
            <div class="col-xl-8">
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
                                                РРЦ: {{$item->retailPrice->price}} @if($item->retailPrice->price > 0)(-{{round(($item->retailPrice->price - $item->price) * 100 / $item->retailPrice->price, 2) }}%)@endif
                                                
                                            </div>
                                            
                                            <div class="my-2">

                                                <a onclick="document.getElementById('order-item-edit-form-{{$item->id}}').classList.toggle('d-none')"
                                                   class="py-0 px-1 btn btn-ms btn-sm @cannot('edit', $item) disabled @endcannot"
                                                   href="javascript:void(0)">
                                                    <i class="fa fa-pencil"></i>
                                                    @lang('site::messages.edit')
                                                </a>

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
                                                      action="{{route('admin.stand-order-items.destroy', $item)}}"
                                                      method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>

                                            </div>
                                            @can('edit', $item)
                                                <fieldset id="order-item-edit-form-{{$item->id}}"
                                                          class="@if(!($errors->any() || $errors->has('standOrderItem.'.$item->id))) d-none @endif border-top mt-4">
                                                    <form method="POST"
                                                          action="{{route('admin.stand-order-items.update', $item)}}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="form-group mt-2 required">
                                                            <label class="control-label" for="price">
                                                                @lang('site::stand_order.item_price')
                                                                {{$item->currency->name}}
                                                            </label>
                                                            <input required
                                                                   type="number" min="0" step="0.01"
                                                                   id="price"
                                                                   value="{{old('standOrderItem.'.$item->id.'.price', $item->price)}}"
                                                                   class="form-control{{ $errors->has('standOrderItem.'.$item->id.'.price') ? ' is-invalid' : '' }}"
                                                                   name="standOrderItem[{{$item->id}}][price]"/>
                                                            <span class="invalid-feedback">{{ $errors->first('standOrderItem.'.$item->id.'.price') }}</span>
                                                        </div>
                                                        <div class="form-group mt-2 required">
                                                            <label class="control-label" for="client">
                                                                @lang('site::stand_order.item_quantity')
                                                            </label>
                                                            <input required
                                                                   type="number"
                                                                   min="1"
                                                                   max="{{config('cart.item_max_quantity')}}"
                                                                   step="1"
                                                                   id="price"
                                                                   value="{{old('standOrderItem.'.$item->id.'.price', $item->quantity)}}"
                                                                   class="form-control{{ $errors->has('standOrderItem.'.$item->id.'.quantity') ? ' is-invalid' : '' }}"
                                                                   name="standOrderItem[{{$item->id}}][quantity]"/>
                                                            <span class="invalid-feedback">{{ $errors->first('standOrderItem.'.$item->id.'.quantity') }}</span>
                                                        </div>
                                                       
                                                        <button class="btn btn-success py-1" type="submit">
                                                            <i class="fa fa-save"></i>
                                                            @lang('site::messages.save')
                                                        </button>
                                                    </form>
                                                </fieldset>
                                            @endcan
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
                    </div>
                @if($standOrder->status_id == 1)
                <div class="card mt-2 mb-4">
                   <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <fieldset class="border p-3" id="parts-search-fieldset">
                                    <div class="form-group required">
                                        <label class="control-label"
                                                   for="parts_search">@lang('site::stand_order.add_item')</label>
                                            <select data-limit="10" id="parts_search" class="form-control">
                                                <option value=""></option>
                                            </select>
                                            <span id="parts_searchHelp" class="d-block form-text text-success">
                                            
                                        </span>
                                    </div>
                                 </div>
                               </fieldset>
                        </div>
                    </div>
                </div>
                
                
                <div class="card mt-2 mb-4">
                <div class="form-group p-3">@lang('site::stand_order.add_items_help')<br />Выбранные для добавления товары:
                </div>
                <form id="item_add"
                      method="POST"
                      enctype="multipart/form-data"
                      action="{{route('admin.stand-orders.update', $standOrder)}}">
                      @csrf
                      @method('PATCH')
                      
                    <div class="form-group">
                    
                        <div class="list-group" id="parts"
                             data-currency-symbol="{{ auth()->user()->currency->symbol_right }}">
                        </div>
                    </div>
                                    
                   
                
                </form>
                
                                    <div class="form-group p-3">
                                        <button form="item_add" type="submit" class="btn btn-ms"><i class="fa fa-check"></i><span>@lang('site::stand_order.add_items')</span></button>
                                    </div> 
                </div>
                @endif   
                <div class="card mt-2 mb-4">
                    <h6 class="card-header with-elements">
                        <span class="card-header-title">@lang('site::message.messages')</span>
                        {{--<div class="card-header-elements ml-auto">--}}
                        {{--<a href="#" class="btn btn-sm btn-light">--}}
                        {{--<i class="fa fa-pencil"></i>--}}
                        {{--</a>--}}
                        {{--</div>--}}
                    </h6>
                    <div class="card-body flex-grow-1 position-relative overflow-hidden">
                        {{--<h5 class="card-title">@lang('site::message.messages')</h5>--}}
                        @if($standOrder->messages->isNotEmpty())
                            <div class="row no-gutters h-100">
                                <div class="d-flex col flex-column">
                                    <div class="flex-grow-1 position-relative">

                                        <!-- Remove `.chat-scroll` and add `.flex-grow-1` if you don't need scroll -->
                                        <div class="chat-messages p-4 ps">

                                            @foreach($standOrder->messages as $message)
                                                <div class="@if($message->user_id == auth()->user()->getAuthIdentifier()) chat-message-right @else chat-message-left @endif mb-4">
                                                    <div>
                                                        <img src="{{$message->user->logo}}"
                                                             style="width: 40px!important;"
                                                             class="rounded-circle" alt="">
                                                        <div class="text-muted small text-nowrap mt-2">{{ $message->created_at->format('d.m.Y H:i') }}</div>
                                                    </div>
                                                    <div class="flex-shrink-1 bg-lighter rounded py-2 px-3 @if($message->user_id == auth()->user()->getAuthIdentifier()) mr-3 @else ml-3 @endif">
                                                        <div class="mb-1"><b>{{$message->user->name}}</b></div>
                                                        <span class="text-big">{!! nl2br($message->text) !!}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <!-- / .chat-messages -->
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        
                        <form action="{{route('admin.stand-orders.message', $standOrder)}}" method="post">
                            @csrf

                            <div class="row no-gutters">
                                <div class="d-flex col flex-column">
                                    <div class="flex-grow-1 position-relative">
                                        <div class="form-group">
                                            <input type="hidden" name="message[receiver_id]"
                                                   value="{{$standOrder->user_id}}">
                                            <textarea name="message[text]"
                                                      id="message_text"
                                                      rows="3"
                                                      placeholder="@lang('site::message.placeholder.text')"
                                                      class="form-control{{  $errors->has('message.text') ? ' is-invalid' : '' }}"></textarea>
                                            <span class="invalid-feedback">{{ $errors->first('message.text') }}</span>
                                        </div>
                                        <button type="submit"
                                                class="btn btn-success d-block d-sm-inline-block">
                                            <i class="fa fa-check"></i>
                                            <span>@lang('site::messages.send')</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-2 mb-2">
                    <div class="card-body">
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
            </div>
        </div>
<h3 id="comments"></h3>   
                @include('site::message.comment', ['commentBox' => $commentBox])

    </div>
@endsection


@push('scripts')
<script>

 try {
        window.addEventListener('load', function () {

            let product = $('#product_id'),
                parts_search = $('#parts_search'),
                parts = $('#parts'),
                selected = [];
            let number_format = function (number, decimals, dec_point, thousands_sep) {

                let i, j, kw, kd, km;

                // input sanitation & defaults
                if (isNaN(decimals = Math.abs(decimals))) {
                    decimals = 0;
                }
                if (dec_point === undefined) {
                    dec_point = ".";
                }
                if (thousands_sep === undefined) {
                    thousands_sep = " ";
                }

                i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

                if ((j = i.length) > 3) {
                    j = j % 3;
                } else {
                    j = 0;
                }

                km = (j ? i.substr(0, j) + thousands_sep : "");
                kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
                //kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
                kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


                return km + kw + kd;
            };
            $(document)
                .on('click', '.part-delete', (function () {
                    let index = selected.indexOf($(this).data('id'));
                    
                        selected.splice(index, 1);
                        $('.product-' + $(this).data('id')).remove();
                    
                   
                }))
                
            let calc_parts = function () {
                let cost = 0;
                parts.children().each(function (i) {
                    let el = $(this).find('.parts_count');
                    cost += (parseInt(el.data('cost')) * el.val());
                });

                $('#total-cost').html(number_format(cost));
            };
            calc_parts();

            parts_search.select2({
                theme: "bootstrap4",
                placeholder: "-- Выбрать --",
                ajax: {
                    url: '/api/stand-items',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            'filter[search_part]': params.term,
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.data,
                        };
                    }
                },
                minimumInputLength: 3,
                templateResult: function (product) {
                    if (product.loading) return "...";
                    let markup = "<img style='width:70px;' src=" + product.image + " /> &nbsp; " + product.name + ' (' + product.sku + ')';
                    return markup;
                },
                templateSelection: function (product) {
                    console.log(product);
                    if (product.id !== "") {
                        return product.name + ' (' + product.sku + ')';
                    } else {
                    return "-- выберите товары --";
                    }


                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
            parts_search.on('select2:select', function (e) {
                let product_id = $(this).find('option:selected').val();
                if (!selected.includes(product_id)) {
                    parts_search.removeClass('is-invalid');
                    selected.push(product_id);
                    axios
                        .get("/api/stand-items/createAdmin/" + product_id)
                        .then((response) => {

                            parts.append(response.data);
                            $('[name="count[' + product_id + ']"]').focus();
                            calc_parts();
                            parts_search.val(null)
                        })
                        .catch((error) => {
                            this.status = 'Error:' + error;
                        });
                } else {
                    parts_search.addClass('is-invalid');
                }
            });


        });
    } catch (e) {
        console.log(e);
    }

</script>
@endpush
