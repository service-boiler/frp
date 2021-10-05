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
            <li class="breadcrumb-item active">@lang('site::user.partner_plus_request.h1')</li>
        </ol>
        <h1 class="header-title mb-4"><i class="fa fa-shopping-cart"></i> @lang('site::user.partner_plus_request.h1')</h1>
        <div class=" border p-3 mb-2">
            <a href="{{ route('partner-plus-requests.user') }}"
               class="d-block d-sm-inline btn mr-0 mr-sm-1 mb-1 mb-sm-0 btn-ms">
                <i class="fa fa-magic"></i>
                <span>@lang('site::user.partner_plus_request.create_new')</span>
            </a>
            <a href="{{ route('home') }}"
               class="d-block d-sm-inline btn btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.home')</span>
            </a>
        </div>
        @alert()@endalert()
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $partnerPlusRequests])@endpagination
        {{$partnerPlusRequests->render()}}

        @foreach($partnerPlusRequests as $partnerPlusRequest)
            <div class="card my-4" id="order-{{$partnerPlusRequest->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">
                        <a href="{{route('partner-plus-requests.show', $partnerPlusRequest)}}" class="mr-3">
                            @lang('site::user.partner_plus_request.request') № {{$partnerPlusRequest->id}}
                        </a>
                        <span class="badge text-normal badge-pill text-white" style="background-color: {{ $partnerPlusRequest->status->color }}">
                            <i class="fa fa-{{ $partnerPlusRequest->status->icon }}"></i> {{ $partnerPlusRequest->status->name }}
                        </span>
                    </div>

                    <div class="card-header-elements ml-md-auto">
                         
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::messages.created_at')</dt>
                            <dd class="col-12">{{$partnerPlusRequest->created_at->format('d.m.Y H:i')}}</dd>

                        </dl>
                    </div>
                    <div class="col-sm-4">
                    <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::user.partner_plus_request.partner')</dt>
                            <dd class="col-12">{{$partnerPlusRequest->partner->name_for_site ? $partnerPlusRequest->partner->name_for_site : $partnerPlusRequest->partner->name}}</dd>
                            <dd class="col-12">{{$partnerPlusRequest->address->locality}}, {{$partnerPlusRequest->address->street}}, {{$partnerPlusRequest->address->building}}</dd>

                        </dl>
                        
                    </div>
                    <div class="col-sm-4">
                    <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::user.partner_plus_request.distributor')</dt>
                            <dd class="col-12">{{$partnerPlusRequest->distributor->name_for_site ? $partnerPlusRequest->distributor->name_for_site : $partnerPlusRequest->distributor->name}}</dd>

                        </dl>
                        
                    </div>
                </div>
            </div>
        @endforeach
        {{$partnerPlusRequests->render()}}
    </div>
@endsection
