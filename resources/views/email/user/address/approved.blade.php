@extends('layouts.email')

@section('title')
    @lang('site::address.email_approved_title')
	 
@endsection

@section('h1')
    @lang('site::address.email_approved_title')
@endsection

@section('body')
    <p><b>@lang('site::address.address')</b>: {{$address->name }}</p>
    <p><b>{{$address->full }}</p>
    <p>
        <a href="{{ route('addresses.show', $address) }}">
            @lang('site::messages.open') {{ route('addresses.show', $address) }}</a>
    </p>
@endsection