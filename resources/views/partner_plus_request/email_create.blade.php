@extends('layouts.email')

@section('title')
    @lang('site::user.partner_plus_request.email_create_title')
@endsection

@section('h1')
    @lang('site::user.partner_plus_request.email_create_h1')
@endsection

@section('body')
    <p><b>@lang('site::user.partner_plus_request.request') №</b>: {{$partnerPlusRequest->id }}</p>
    <p><b>@lang('site::user.partner_plus_request.status')</b>: {{$partnerPlusRequest->status->name }}</p>
    <p><b>@lang('site::user.partner_plus_request.partner')</b>: {{$partnerPlusRequest->partner->name }}</p>
    <p><b>@lang('site::user.partner_plus_request.distributor')</b>: {{$partnerPlusRequest->distributor->name }}</p>
    <p><b>@lang('site::user.partner_plus_request.created_by_id')</b>: {{$partnerPlusRequest->creator->name }}</p>
    <p>
        <a class="btn btn-ms btn-lg" href="{{ route('partner-plus-requests.show', $partnerPlusRequest) }}">
            &#128194; @lang('site::messages.open') @lang('site::user.partner_plus_request.request') {{ route('partner-plus-requests.show', $partnerPlusRequest) }}</a>
    </p>
    
@endsection