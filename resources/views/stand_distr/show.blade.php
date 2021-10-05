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
                <a href="{{ route('stand-distr.index') }}">@lang('site::stand_order.orders_distr')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::stand_order.breadcrumb_show', ['stand_order' => $standOrder->id, 'date' => $standOrder->created_at->format('d.m.Y H:i') ])</li>
        </ol>
        <h1 class="header-title mb-4">№ {{ $standOrder->id }}</h1>
        @alert()@endalert()

        <div class=" border p-3 mb-2">
        <div class="row">
            <div class="col-3">
            <a href="{{ route('stand-distr.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            </div>
            <div class="col-9 text-right pt-2">
                <span class="text-muted">@lang('site::stand_order.status_id'): {{ $standOrder->status->id }}</span>&nbsp;
                <span class="badge text-normal badge-pill text-white"
                                  style="background-color: {{ $standOrder->status->color }}">
                                <i class="fa fa-{{ $standOrder->status->icon }}"></i> {{ $standOrder->status->name }}
                </span>
            </div>
        </div>
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
                            
                            
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.contragent_id'):</span>&nbsp;
                            <span class="text-dark">{{ $standOrder->contragent->name }}</span><br />
                            <span class="text-dark">({{ $standOrder->user->name }})</span><br />
                            <span class="text-dark">ИНН: {{ $standOrder->contragent->inn }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.email'):</span>&nbsp;
                            <span class="text-dark">{{$standOrder->user->email}}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.contact'):</span>&nbsp;
                            <span class="text-dark">{{$standOrder->user->contacts->first()->phones->first()->number}}</span>
                        </div>

                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.address_id'):</span>&nbsp;
                            <span class="text-dark">{{ optional($standOrder->address)->name }} {{ optional($standOrder->address)->full }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.warehouse_address_id'):</span>&nbsp;
                            <span class="text-dark">{{ optional($standOrder->warehouse_address)->name }}</span>
                        </div>

                        <hr class="p-0"/>

                        <div class="mb-2">
                            <div class="font-weight-bold mb-0">@lang('site::stand_order.total'):</div>&nbsp;
                            <div class="text-dark display-6 font-weight-bolder">
                                @include('site::stand_order.total')
                            </div>
                        </div>
                        <hr class="p-0"/>
                        <div class="mb-2">
                            <div class="font-weight-bold mb-0">
                                @lang('site::stand_order.payment')
                            </div>
                            @if($standOrder->file)
                            <div class="mb-2">
                                <div class="form-row bg-white">
                                    @include('site::file.download', ['file' => $standOrder->file])
                                </div>
                            </div>
                            @endif
                            @if(in_array($standOrder->status_id, array(1,2,3)))
                                <div class="form-row mt-2">
                                    <div class="col">

                                        <form method="POST" enctype="multipart/form-data"
                                              action="{{route('stand-distr.payment', $standOrder)}}">
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
                            <form action="{{route('stand-distr.update', $standOrder)}}"
                                  method="POST" id="status">
                                @csrf
                                @method('PATCH')
                                <div class="d-flex flex-column justify-content-between">
                                
                                @foreach($statuses as $key => $status)
                                    <button type="submit"
                                            name="standOrder[status_id]"
                                            value="{{$status->id}}"
                                            form="status"
                                            style="background-color: {{$status->color}};color:white"
                                            class="btn mb-1">
                                        <i class="fa fa-{{$status->icon}}"></i>
                                        <span>{{$status->button}}</span>
                                    </button>
                                @endforeach
                                
                                    
                                    
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
            </div>
        </div>
    </div>
@endsection
