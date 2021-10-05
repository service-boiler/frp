@extends('layouts.email')

@section('title')
   @lang('site::stand_order.email_create_title')
@endsection

@section('h1')
    @lang('site::stand_order.email_create_h1')
@endsection

@section('body')
    <p><b>@lang('site::stand_order.id')</b>: {{$standOrder->id }}</p>
    <p><b>@lang('site::stand_order.user_id')</b>: {{$standOrder->user->name }}</p>
    <p><b>{{ $standOrder->address->type->name }}</b>: {{ $standOrder->address->name }} </p>
    <p>
        <a class="btn btn-ms btn-lg" href="{{ route('stand-orders.index') }}">
            &#128194; @lang('site::stand_order.orders') {{ route('stand-orders.index') }}</a>
    </p>
@endsection