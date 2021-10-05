@extends('layouts.email')

@section('title')
    @lang('site::address.updated')
@endsection

@section('h1')
    @lang('site::address.updated')
@endsection

@section('body')
    <p><b>Компания</b>: {{$address->user->name }}</p>
	 <p>
        <a href="{{ route('admin.addresses.show', $address) }}">
           Открыть в админке {{ route('admin.addresses.show', $address) }}</a>
    </p>
	 <p> Адрес нужно проверить и поставить необходимые галки. В данный момент адрес исключен из списка на сайте.
	 </p>
@endsection