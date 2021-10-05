@extends('layouts.email')

@section('title')
    @lang('site::mounting.email.create.title')
@endsection

@section('h1')
    @lang('site::mounting.email.create.h1')
@endsection

@section('body')
    <p><b>@lang('site::mounting.user_id')</b>: {{$mounting->user->name }}</p>
    <p>
        <a class="btn btn-ms btn-lg" href="{{ route('admin.mountings.show', $mounting) }}">
            &#128194; @lang('site::messages.open') @lang('site::mounting.mounting') № {{$mounting->id }}</a>
    </p>
@endsection