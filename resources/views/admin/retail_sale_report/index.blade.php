@extends('layouts.app')
@section('title')Отчеты о продаже @endsection
@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::retail_sale_report.reports')</li>
        </ol>
        
        @alert()@endalert()

        <div class=" border p-3 mb-2">
            <button form="repository-form"
                    type="submit"
                    name="excel"
                    class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-primary">
                <i class="fa fa-upload"></i>
                <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
            </button>
            <a href="{{ route('ferroli-user.home') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>

        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $retail_sale_reports])@endpagination
        {{$retail_sale_reports->render()}}
        @foreach($retail_sale_reports as $retail_sale_report)
            <div class="card my-4" id="retail_sale_report-{{$retail_sale_report->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">
                        <a href="{{route('admin.retail-sale-reports.show', $retail_sale_report)}}" class="mr-2 ml-0">
                            @lang('site::retail_sale_report.report') № {{$retail_sale_report->id}}
                        </a>
                        <span class="badge text-normal badge-pill badge-{{ $retail_sale_report->status->color }} mr-3 ml-0">
                            <i class="fa fa-{{ $retail_sale_report->status->icon }}"></i> {{ $retail_sale_report->status->name }}
                        </span>
		@if($retail_sale_report->digiftBonus)
		@if($retail_sale_report->digiftBonus->sended) <span class="badge text-normal badge-pill badge-success mr-3 ml-0">{{$retail_sale_report->digiftBonus->operationValue}} отправлено в Digift</span>
		@endif
		@if($retail_sale_report->digiftBonus->blocked) <span class="badge text-normal badge-pill badge-danger mr-3 ml-0">Бонус блокирован</span>
		@endif
		@endif
                    </div>

                    <div class="card-header-elements ml-md-auto">
                        <a href="{{route('admin.users.show', $retail_sale_report->user)}}" class="mr-3 ml-0">
                            <img id="user-logo"
                                 src="{{$retail_sale_report->user->logo}}"
                                 style="width:25px!important;height: 25px"
                                 class="rounded-circle mr-2">{{$retail_sale_report->user->name}}
                        </a>
                        @if( $retail_sale_report->messages()->exists())
                            <span class="badge badge-secondary text-normal badge-pill">
                                <i class="fa fa-comment"></i> {{ $retail_sale_report->messages()->count() }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-2 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::retail_sale_report.created_at')</dt>
                            <dd class="col-12">{{$retail_sale_report->created_at->format('d.m.Y')}}</dd>
                            <dt class="col-12">@lang('site::retail_sale_report.date_trade')</dt>
                            <dd class="col-12">{{$retail_sale_report->date_trade->format('d.m.Y')}}</dd>
                            
                        </dl>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::retail_sale_report.product_id')</dt>
                            <dd class="col-12">{{$retail_sale_report->product->name}}</dd>
                            <dt class="col-12">@lang('site::retail_sale_report.serial_id')</dt>
                            <dd class="col-12">
                            <dd class="col-12">
                                @if($retail_sale_report->serial_id)
                                    <div class="bg-light p-2">
				   @if($retail_sale_report->duplicates_serial->isNotEmpty())
                                        {{$retail_sale_report->serial_id}}
                                        <a href="{{route('admin.retail-sale-reports.index', ['filter[search_serial]' => $retail_sale_report->serial_id])}}">
                                         <span data-toggle="tooltip" data-placement="top" title="@lang('site::retail_sale_report.help.duplicates')" class="badge text-normal badge-pill badge-danger">
                                        <i class="fa fa-copy"></i> {{$retail_sale_report->duplicates_serial->count()}}
                                        </span></a>
                                        <br /><span class="bg-danger text-white px-2">@lang('site::retail_sale_report.serial_dup')</span>
                                @else
					{{$retail_sale_report->serial_id}}
				@endif
				</div>
                                    @if($retail_sale_report->serial()->exists())
                                        <div class="text-muted">{{$retail_sale_report->serial->product->name}}</div>
                                        <div class="text-muted">{{$retail_sale_report->serial->comment}}</div>
                                    @else
                                        <span class="bg-danger text-white px-2">@lang('site::serial.error.not_found')</span>
                                    @endif
                                @else
                                    <span class="bg-danger text-white px-2">@lang('site::serial.error.not_exist')</span>
                                @endif
                            </dd>
                            
                        </dl>
                    </div>
                    <div class="col-xl-5 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            
                            <dt class="col-12">@lang('site::retail_sale_report.address_id')</dt>
                            <dd class="col-12">{!! !empty($retail_sale_report->address) ? $retail_sale_report->address->full .' ' .$retail_sale_report->address->name : $retail_sale_report->address_new .' <span class="text-danger">! новый адрес!</span>' !!}</dd>
	                    

			    @if($retail_sale_report->comment)
                                <dt class="col-12">@lang('site::retail_sale_report.comment')</dt>
                                <dd class="col-12">{{$retail_sale_report->comment}}</dd>
                            @endif
                        </dl>
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::retail_sale_report.retail_sale_bonus.retail_sale_bonus')</dt>
                            <dd class="col-12">
                                {{number_format($retail_sale_report->bonus, 0, '.', ' ')}}
                                {{ $retail_sale_report->user->currency->symbol_right }}
                            </dd>
                            
                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
        {{$retail_sale_reports->render()}}
    </div>
@endsection
