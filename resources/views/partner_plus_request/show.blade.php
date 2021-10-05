@extends('layouts.app')
@section('title') @lang('site::user.partner_plus_request.request') № {{$partnerPlusRequest->id}} ({{$partnerPlusRequest->partner->name}})@endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('partner-plus-requests.index') }}">@lang('site::user.partner_plus_request.h1')</a>
            </li>
            <li class="breadcrumb-item active">№ {{ $partnerPlusRequest->id }}</li>
        </ol>
        @alert()@endalert()
        
        
        @if($statuses->isNotEmpty())
        <div class=" border p-2 mb-3">
            
            <form id="form_status"
                      method="POST"
                      enctype="multipart/form-data"
                      action="{{ route('partner-plus-requests.update', $partnerPlusRequest) }}">
                    @csrf
                    @method('PUT')
                    
                <a href="{{route('partner-plus-requests.edit', $partnerPlusRequest)}}"
                   class="d-block d-sm-inline mr-0 mr-sm-1 btn btn-ms p-2 @cannot('update',$partnerPlusRequest) disabled @endcannot">
                    <i class="fa fa-pencil"></i>
                    <span>@lang('site::messages.edit')</span>
                </a>
                @if(in_array($partnerPlusRequest->status->id,[111]))
                <a href="{{ route('partner-plus-requests.pdf', $partnerPlusRequest) }}"
                    class="d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0 p-2 btn btn-primary">
                        <i class="fa fa-print"></i>
                        <span>@lang('site::messages.print')</span>
                </a>
                @endif
                
            <a href="{{ route('partner-plus-requests.index') }}" class="d-block d-sm-inline btn btn-secondary p-2 mr-sm-3 ">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a> 
              
                        @foreach($statuses as $key => $status)
                            <button type="submit"
                                    name="status_id"
                                    value="{{$status->id}}"
                                    form="form_status"
                                    style="background-color: {{$status->color}};color:white"
                                    class="d-block d-sm-inline mr-0 mt-2 mr-sm-1 mb-sm-2  btn">
                                <i class="fa fa-{{$status->icon}}"></i>
                                <span>{{$status->button}}</span>
                            </button>
                        @endforeach
    
            
        </form>
        </div>
        @endif <!--{{--$statuses->isNotEmpty()--}} -->
        
        
        <div class="card mb-2">
            <div class="card-body pb-0">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-left text-sm-right">Статус заявки</dt>
                    <dd class="col-sm-8">
                        <span class="badge text-normal badge-{{$partnerPlusRequest->status->color}}">
                            <i class="fa fa-{{$partnerPlusRequest->status->icon}}"></i>
                            {{ $partnerPlusRequest->status->name }}
                        </span>
                    </dd>
                    <dt class="col-sm-4 text-left text-sm-right">Дата создания заявки</dt>
                    <dd class="col-sm-8">{{ $partnerPlusRequest->created_at->format('d.m.Y H:i') }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">Создатель заявки</dt>
                    <dd class="col-sm-8">{{ $partnerPlusRequest->creator->name }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.partner_plus_request.partner')</dt>
                    <dd class="col-sm-8">{{ $partnerPlusRequest->partner->name_for_site ? $partnerPlusRequest->partner->name_for_site : $partnerPlusRequest->partner->name}}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.partner_plus_request.address')</dt>
                    <dd class="col-sm-8">{{ $partnerPlusRequest->address->full }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.partner_plus_request.contragent')</dt>
                    <dd class="col-sm-8">{{ $partnerPlusRequest->contragent->name }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.partner_plus_request.distributor')</dt>
                    <dd class="col-sm-8">{{ $partnerPlusRequest->distributor->name }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.partner_plus_request.found_year')</dt>
                    <dd class="col-sm-8">{{ $partnerPlusRequest->found_year }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.partner_plus_request.sales_staff')</dt>
                    <dd class="col-sm-8">{{ $partnerPlusRequest->sales_staff }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.partner_plus_request.shop_count')</dt>
                    <dd class="col-sm-8">{{ $partnerPlusRequest->shop_count }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.partner_plus_request.annual_turnover')</dt>
                    <dd class="col-sm-8">{{ $partnerPlusRequest->annual_turnover }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.partner_plus_request.warehouse_area')</dt>
                    <dd class="col-sm-8">{{ $partnerPlusRequest->warehouse_area }}</dd>
                     
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.partner_plus_request.warehouse_count')</dt>
                    <dd class="col-sm-8">{{ $partnerPlusRequest->warehouse_count }}</dd>
                     
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.partner_plus_request.has_service')</dt>
                    <dd class="col-sm-8">{{ $partnerPlusRequest->has_service ? 'Да' : 'Нет' }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.partner_plus_request.has_mounters')</dt>
                    <dd class="col-sm-8">{{ $partnerPlusRequest->has_mounters ? 'Да' : 'Нет' }}</dd>
                    
                    
                    
                </dl>   
            </div>
        </div>
        
        
        {{--
        <div class="card mb-2">
            <div class="card-body pb-0">
                <h5 class="card-title">Прикрепленные файлы</h5>
                @include('site::file.files')
            </div>
        </div> --}}
        @include('site::message.create', ['messagable' => $partnerPlusRequest])
        
        @can('comm',$partnerPlusRequest)
        <h3 id="end"></h3>   
                @include('site::message.comment', ['commentBox' => $commentBox])
        @endcan              
                 
        
    </div>
@endsection

