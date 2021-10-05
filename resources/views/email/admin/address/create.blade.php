@extends('layouts.email')

@section('title')
    Оформлен новый @lang('site::address.address')
@endsection

@section('h1')
    Оформлен новый @lang('site::address.address')
@endsection


@section('body')
    <p><b>Компания</b>: {{$address->user->name }}</p>
    <p>
        <a class="btn btn-ms btn-lg" href="{{ route('admin.addresses.show', $address) }}">
            &#128194; Открыть @lang('site::address.address')</a>
    </p>
@endsection