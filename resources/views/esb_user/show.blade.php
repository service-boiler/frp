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
                <a href="{{ route('esb-users.index') }}">@lang('site::user.esb_user.index')</a>
            </li>
            <li class="breadcrumb-item active">{{ $esbUser->name }}</li>
        </ol>
        @alert()@endalert()
        <div class="p-3 mb-0">
            <a class="btn btn-ms d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('esb-users.edit', $esbUser) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit')</span>
            </a>
            <a href="{{ route('esb-users.index') }}" class="d-block d-sm-inline-block btn btn-secondary mr-sm-3">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            
            <button
            class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 btn btn-danger btn-row-delete mt-1"
                    data-form="#delete-form"
                    data-btn-delete="@lang('site::messages.delete')"
                    data-btn-cancel="@lang('site::messages.cancel')"
                    data-label="@lang('site::messages.delete_confirm')"
                    data-message="@lang('site::messages.delete_sure') @lang('site::user.asb_user.launch')? "
                    data-toggle="modal" data-target="#form-modal"
                    title="@lang('site::messages.delete')">
                <i class="fa fa-close"></i>
                @lang('site::messages.delete')
            </button>
            <form id="delete-form"
                      method="POST"
                      action="{{ route('esb-users.destroy',$esbUser) }}">
                    @csrf
                    @method('DELETE')
                    
                    </form>
        </div>
        <div class="card mb-4">
            <div class="card-body">

                <dl class="row">

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_user.name')</dt>
                    <dd class="col-sm-8">{{ $esbUser->name }}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.phone')</dt>
                    <dd class="col-sm-8">{{ $esbUser->phone }}</dd>

                </dl>
                
                
            </div>
        </div>
        <h4>Оборудование клиента</h4>
        @foreach($esbUser->esbProducts->where('enabled',1) as $esbUserProduct)
        <div class="card mb-4">
            <div class="card-body">

                <h4><a href="{{route('esb-user-products.show',$esbUserProduct)}}">{{$esbUserProduct->product ? $esbUserProduct->product->name : $esbUserProduct->product_no_cat}}</a></h4>
                                   <dl class="row mb-0"> 
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.serial')</dt>
                                        <dd class="col-sm-9">{{$esbUserProduct->serial}}</dd>
                                        
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.date_sale')</dt>
                                        <dd class="col-sm-9">{!!$esbUserProduct->date_sale ? $esbUserProduct->date_sale->format('d.m.Y') : '<span class="text-danger">Нет данных</span>'!!}</dd>
                                        
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.date_launch')</dt>
                                        <dd class="col-sm-9">{!!$esbUserProduct->launch() ? $esbUserProduct->launch()->date_launch->format('d.m.Y') : '<span class="text-danger">Нет данных</span>'!!}</dd>
                                        
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.to')</dt>
                                        
                                        <dd class="col-sm-9">@if($esbUserProduct->maintenances()->exists())
                                                                @foreach($esbUserProduct->maintenances()->orderByDesc('date')->get() as $mt)
                                                                    {{$mt->date->format('d.m.Y')}}<br />
                                                                @endforeach
                                                            @else
                                                             нет данных
                                                            @endif
                                                            
                                        </dd>
                                        
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.address_id')</dt>
                                        <dd class="col-sm-9">{{$esbUserProduct->address ? $esbUserProduct->address->full : ''}}</dd>
                                        
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.product_second_data')</dt>
                                        <dd class="col-sm-9">{!!$esbUserProduct->product_no_cat!!}</dd>
                                        
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.product_second_data')</dt>
                                        <dd class="col-sm-9">
                                        
                                        
                                        @if($esbUserProduct->warranty['type'] == 'mainextended')
                                            Действует стандартная (до {{$esbUserProduct->warranty_main_before}}) и дополнительная гарантия (до {{$esbUserProduct->warranty_extended_before}})
                                        @elseif($esbUserProduct->warranty['type'] == 'extended')
                                                Действует дополнительная гарантия до {{$esbUserProduct->warranty_extended_before}}
                                               <br />@lang('site::user.esb_user_product.warranty_year_text.'.$esbUserProduct->up_time_year)
                                        @elseif($esbUserProduct->warranty['type'] == 'standart')
                                            Действует стандартная гарантия до {{$esbUserProduct->warranty_main_before}}
                                             
                                        @elseif($esbUserProduct->product->warranty)
                                            Гарантия не действует
                                            {{$esbUserProduct->warranty['error']}}
                                            @else
                                            Нет данных о гарантии
                                        @endif
                                        </dd>
                                        
                                    </dl>
                
                
            </div>
        </div>
        @endforeach
    </div>
@endsection
