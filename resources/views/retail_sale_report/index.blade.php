@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::retail_sale_report.reports')</li>
        </ol>
        <h1 class="header-title mb-4">
            <i class="fa fa-@lang('site::retail_sale_report.icon')"></i> @lang('site::retail_sale_report.reports')
        </h1>

        @alert()@endalert()

        <div class=" border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('retail-sale-reports.create') }}"
               role="button">
                <i class="fa fa-magic"></i>
                <span>@lang('site::messages.create') @lang('site::retail_sale_report.report')</span>
            </a>
			@if($current_user->type_id != 3)
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('acts.create') }}"
               role="button">
                <i class="fa fa-magic"></i>
                <span>@lang('site::messages.create') @lang('site::act.act')</span>
            </a>
			@endif
            <a href="{{ route('home') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_home')</span>
            </a>
        </div>

        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $retail_sale_reports])@endpagination
        {{$retail_sale_reports->render()}}

        @foreach($retail_sale_reports as $retail_sale_report)
            <div class="card my-4" id="retail_sale_report-{{$retail_sale_report->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">
                        <span class="badge text-normal badge-pill badge-{{ $retail_sale_report->status->color }}">
                            <i class="fa fa-{{ $retail_sale_report->status->icon }}"></i> {{ $retail_sale_report->status->name }}
                        </span>
                        <a href="{{route('retail-sale-reports.show', $retail_sale_report)}}" class="ml-3">
                            @lang('site::retail_sale_report.report') № {{$retail_sale_report->id}}
                        </a>
                    </div>
                    <div class="card-header-elements ml-md-auto">
                        @if( $retail_sale_report->messages()->exists())
                            <span class="badge badge-secondary text-normal badge-pill">
                                <i class="fa fa-comment"></i> {{ $retail_sale_report->messages()->count() }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-2 col-md-3 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::retail_sale_report.created_at')</dt>
                            <dd class="col-12">{{$retail_sale_report->created_at->format('d.m.Y')}}</dd>
                            <dt class="col-12">@lang('site::retail_sale_report.date_trade')</dt>
                            <dd class="col-12">{{$retail_sale_report->date_trade->format('d.m.Y')}}</dd>
                        </dl>
                    </div>
                    <div class="col-xl-3 col-md-3 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::retail_sale_report.product_id')</dt>
                            <dd class="col-12">{{$retail_sale_report->product->name}}</dd>
                            <dt class="col-12">@lang('site::retail_sale_report.serial_id')</dt>
                            <dd class="col-12">{{$retail_sale_report->serial_id}}</dd>
                        </dl>
                    </div>
                    <div class="col-xl-5 col-md-3 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::retail_sale_report.address_id')</dt>
                            <dd class="col-12">{{!empty($retail_sale_report->address) ? $retail_sale_report->address->full : $retail_sale_report->address_new}}</dd>
                        </dl>
                    </div>
                    <div class="col-xl-2 col-md-3 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::retail_sale_report.retail_sale_bonus.retail_sale_bonus')</dt>
                            <dd class="col-12">
                                {{number_format($retail_sale_report->bonus, 0, '.', ' ')}}
                                @lang('site::retail_sale_report.retail_sale_bonus.symbol_large')
                            </dd>
                            
                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
        {{$retail_sale_reports->render()}}
    </div>
@endsection
