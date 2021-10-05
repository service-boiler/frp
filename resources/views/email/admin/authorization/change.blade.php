@extends('layouts.email')

@section('title')
    @lang('site::authorization.email.title')
@endsection

@section('h1')
    @lang('site::authorization.email.edit.h1')
@endsection

@section('body')
    <p><b>@lang('site::authorization.id')</b>: {{$authorization->id }}</p>
    <p><b>@lang('site::authorization.status_id')</b>: {{ $authorization->status->name }}</p>
	<p>@lang('site::authorization.role_id'):{{ $authorization->role->authorization_role->name }}</p>
	<p>{{ $authorization->role->authorization_role->title }}
                            @foreach($authorization->types as $authorization_type)
                                <li>{{ $authorization_type->name }} {{ $authorization_type->brand->name }}</li>
                            @endforeach
    
@endsection