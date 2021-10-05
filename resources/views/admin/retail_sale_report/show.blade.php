@extends('layouts.app')
@section('title')Отчет о продаже {{ $retail_sale_report->id }} {{$retail_sale_report->user->name}} @endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.retail-sale-reports.index') }}">@lang('site::retail_sale_report.reports')</a>
            </li>
            <li class="breadcrumb-item active">№ {{ $retail_sale_report->id }} {{$retail_sale_report->user->name}}  {{$retail_sale_report->user->region->name}} </li>
        </ol>
        
        @alert()@endalert()
        <div class=" border p-3 mb-4">
            @if($retail_sale_report_statuses->isNotEmpty())
                @foreach($retail_sale_report_statuses as $retail_sale_report_status)
                    <button type="submit"
                            form="retail_sale_report-status-edit-form"
                            name="retail_sale_report[status_id]"
                            value="{{$retail_sale_report_status->id}}"
                            class="btn btn-{{$retail_sale_report_status->color}} d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0">
                        <i class="fa fa-{{$retail_sale_report_status->icon}}"></i>
                        <span>{{$retail_sale_report_status->button}}</span>
                    </button>
                @endforeach
            @endif
            <a href="{{ route('admin.retail-sale-reports.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>

        </div>

        <form id="retail_sale_report-status-edit-form"
              action="{{route('admin.retail-sale-reports.update', $retail_sale_report)}}"
              method="POST">
            @csrf
            @method('PUT')
            
        </form>

        @include('site::message.create', ['messagable' => $retail_sale_report])

        <div class="card mb-2">
            <div class="card-body">
                <h5 class="card-title">@lang('site::retail_sale_report.report')</h5>
                <dl class="row">

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::retail_sale_report.created_at')</dt>
                    <dd class="col-sm-8">{{ $retail_sale_report->created_at->format('d.m.Y H:i') }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">Продавец</dt>
                    <dd class="col-sm-8">{{ $retail_sale_report->user->name }} 
                                @foreach($retail_sale_report->user->roles as $role) 
                                    <span class="d-inline-block text-normal text-success ml-2"> ✔ </span>{{$role->title}}
                                @endforeach
                                </dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">Компания продавца</dt>
                    <dd class="col-sm-8">
                        @if($retail_sale_report->user->parent)
                                <a target="_blank" href="{{route('admin.users.show',$retail_sale_report->user->parent)}}">{{ $retail_sale_report->user->parent->name }}</a>
                                <br />
                                @foreach($retail_sale_report->user->parent->roles as $role) 
                                    <span class="d-inline-block text-normal text-success ml-2"> ✔ </span>{{$role->title}}
                                @endforeach
                        @else
                        Пользователь не привязан к компании
                        @endif</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::retail_sale_report.status_id')</dt>
                    <dd class="col-sm-8">
                        <span class="badge text-normal badge-{{$retail_sale_report->status->color}}">
                            <i class="fa fa-{{$retail_sale_report->status->icon}}"></i>
                            {{ $retail_sale_report->status->name }}
                        </span>
                    </dd>

                </dl>

                <hr/>
                <dl class="row">

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::retail_sale_report.serial_id')</dt>
                    <dd class="col-sm-8">{{ $retail_sale_report->serial_id }}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::serial.product_id')</dt>
                    <dd class="col-sm-8">{{ $retail_sale_report->product->name }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::retail_sale_report.address_id')</dt>
                    <dd class="col-sm-8">
                        @if(!empty($retail_sale_report->address))
                            <a target="_blank" href="{{route('admin.addresses.show', $retail_sale_report->address)}}">{!! $retail_sale_report->address->full !!}</a>
                            <br />
                            @bool(['bool' => $retail_sale_report->address->approved])@endbool  @lang('site::messages.approved') &nbsp; &nbsp; &nbsp;
                            @bool(['bool' => $retail_sale_report->address->show_ferroli])@endbool  @lang('site::messages.show_ferroli') &nbsp; &nbsp; &nbsp;
                            @bool(['bool' => $retail_sale_report->address->is_shop])@endbool @lang('site::address.is_shop') &nbsp; &nbsp; &nbsp;
                            @bool(['bool' => $retail_sale_report->address->is_service])@endbool @lang('site::address.is_asc') &nbsp; &nbsp; &nbsp;
                            
                            @else {{ $retail_sale_report->address_new }} <span class="text-danger">! новый адрес!</span>
                            @endif
                            </dd>

                </dl>
            
                <dl class="row">

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::retail_sale_report.retail_sale_bonus.retail_sale_bonus')</dt>
                    <dd class="col-sm-8 ">
                        {{number_format($retail_sale_report->bonus, 0, '.', ' ')}}
                        @lang('site::retail_sale_report.retail_sale_bonus.symbol_large')
                    </dd>


                </dl>
            </div>
        </div>
        @include('site::admin.digift_bonus.user', ['bonusable' => $retail_sale_report])
        
      
        
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">@lang('site::retail_sale_report.files')</h5>
                @include('site::file.files')
            </div>
        </div>
        @include('site::message.comment', ['commentBox' => $commentBox])
    </div>
@endsection