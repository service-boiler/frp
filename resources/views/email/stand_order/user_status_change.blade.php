@extends('layouts.email')

@section('title')
    @lang('site::stand_order.email_status_change_title')
@endsection

@section('h1')
    @lang('site::stand_order.email_status_change_h1')
@endsection

@section('body')
    <p><b>@lang('site::stand_order.id')</b>: {{$standOrder->id }}</p>
    <p><b>@lang('site::stand_order.status_id')</b>: {{$standOrder->status->name }}</p>
    <p>
        <a class="btn btn-ms btn-lg" href="{{ route('stand-orders.show', $standOrder) }}">
            &#128194; @lang('site::messages.open') @lang('site::stand_order.order') {{ route('stand-orders.show', $standOrder) }}</a>
    </p>
@endsection