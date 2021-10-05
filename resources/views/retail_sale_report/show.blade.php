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
                <a href="{{ route('retail-sale-reports.index') }}">@lang('site::retail_sale_report.reports')</a>
            </li>
            <li class="breadcrumb-item active">№ {{ $retail_sale_report->id }}</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::retail_sale_report.report') № {{ $retail_sale_report->id }}</h1>
        @alert()@endalert()
        <div class=" border p-3 mb-4">

            <a href="{{ route('retail-sale-reports.index') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>

        </div>
        @include('site::message.create', ['messagable' => $retail_sale_report])
        <div class="card mb-2">
            <div class="card-body">
                <h5 class="card-title">@lang('site::retail_sale_report.report')</h5>
                <dl class="row">

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::retail_sale_report.created_at')</dt>
                    <dd class="col-sm-8">{{ $retail_sale_report->created_at->format('d.m.Y H:i') }}</dd>

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


                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::serial.product_id')</dt>
                    <dd class="col-sm-8">{{ $retail_sale_report->product->name }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::retail_sale_report.retail_sale_bonus.retail_sale_bonus')</dt>
                    <dd class="col-sm-8">{{ $retail_sale_report->bonus }} @lang('site::retail_sale_report.retail_sale_bonus.symbol_large')</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::retail_sale_report.date_trade')</dt>
                    <dd class="col-sm-8">{{ $retail_sale_report->date_trade->format('d.m.Y') }}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::retail_sale_report.serial_id')</dt>
                    <dd class="col-sm-8">{{ $retail_sale_report->serial_id }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::retail_sale_report.address_id')</dt>
                    <dd class="col-sm-8">{{!empty($retail_sale_report->address) ? $retail_sale_report->address->full : $retail_sale_report->address_new}}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::retail_sale_report.comment')</dt>
                    <dd class="col-sm-8">{{ $retail_sale_report->comment }}</dd>
                    
                </dl>
            </div>
        </div>
        

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">@lang('site::retail_sale_report.files')</h5>
                @include('site::file.files')
            </div>
        </div>
    </div>
@endsection
